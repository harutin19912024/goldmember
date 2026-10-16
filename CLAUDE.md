# Goldmember — Claude Context

> **Start every session by verifying Docker is running:**
> ```bash
> docker compose ps   # should show 3 containers: app, db, phpmyadmin
> docker compose up -d   # start if stopped
> ```
>
> **Current state (2026-10-16):** work from the audit + 5 sprints sits on stacked branches
> `sprint-1-security` → `sprint-2-broken-pages` → `sprint-3-data-ops` → `sprint-4-seo-i18n-perf` → `sprint-5-cleanup`
> (not merged to `main`; sprint-4 is pushed to origin; **not deployed**). Full report + production deploy checklist:
> **[`docs/PROGRESS-2026-10-16.md`](docs/PROGRESS-2026-10-16.md)**.

---

## What This Project Is

**Goldmember** (`goldmember.am`) is a gold/precious-metals marketplace and trading platform targeting the Armenian market. Built on **Yii2 Advanced Template** (PHP 8.2) with MariaDB, served via Apache inside Docker.

### Business Features
- Product catalog of gold/jewelry items (photos, pricing, fineness, weight, karat)
- Auction system with live Agora video streaming per lot
- Best Offer listings
- Real-time metal prices for gold/silver/platinum/palladium, per karat
- Currency exchange rates (AMD, USD, EUR, RUB)
- Customer registration, login, favorites, order history
- Multi-language: Armenian (`am`, default) + English (`en`)
- News/blog section
- AI chat widget (OpenAI Responses API, scoped to platform topics)
- Donate / "Power of Penny" fundraising
- Backend admin panel for all content management

---

## Architecture

```
/var/www/html/goldmember/
├── backend/          → Admin panel   (http://localhost:8090)
│   ├── controllers/  → 46 controllers (incl. all Tr* translation pairs)
│   ├── models/       → ActiveRecord models (backend-specific)
│   ├── views/        → 300+ view files; all section index.php now use
│   │                   product canonical "table-layout > tray > panel" pattern
│   └── web/          → entry point: index.php
│
├── frontend/         → Customer site  (http://localhost:8080)
│   ├── controllers/  → SiteController, ProductController, AgoraController,
│   │                   ChatController, etc.
│   ├── models/       → Frontend-specific models (LoginForm, DonateForm, ...)
│   ├── services/     → ChatBotService (OpenAI integration)
│   ├── views/        → 118+ view files
│   └── web/          → entry point: index.php
│
├── common/           → Shared between frontend + backend
│   ├── models/       → User, Language, Product, Customer, MetalPriceReal, ...
│   ├── components/   → VisitorTracker, CurrencyHelper, UserComponent, agora/
│   └── config/       → db-local.php, main.php, params.php
│
├── docker/           → Docker configs (Apache vhosts, PHP ini, MySQL init)
├── docker-compose.yml
├── Dockerfile        → PHP 8.2-apache
├── updateExchange.php → Cron-ready script: fetches XAU/XAG/XPT/XPD prices
│                       from metalpriceapi.com → metal_price_real
├── .env / .env.example → Local secrets (CHATBOT_API_KEY etc.)
└── gssamru_goldmember.sql  → DB dump (import on first start)
```

### Key Design Choices
- **Yii2 Advanced Template** — backend and frontend are separate Yii2 apps sharing `common/`
- **codemix/yii2-localeurls** — URL language prefixes (`/am/...`, `/en/...`); URL code `am` ≠ ICU locale `am` (Amharic), so the formatter is pinned to `en-US` in `backend/config/main.php`
- **Timezone** — both apps set `'timeZone' => 'Asia/Yerevan'` at the top of their `main.php`. Yii calls PHP `date_default_timezone_set()` from this, so every `strtotime()`/`date()` interprets DB datetimes as Yerevan local (matches what admins type into datepickers). MariaDB session tz is still UTC; no code does server-side date math beyond PHP.
- **Session-based cart** — using `omnilight/yii2-shopping-cart` + manual session keys
- **VisitorTracker** — bootstrap component tracking every request in `user_activity` table
- **Multi-model translations** — pattern `Product` + `TrProduct`, `News` + `TrNews`, etc.
  - Each translatable section has: a `Tr<Name>Controller` with AJAX `actionUpdate` that returns the form partial; a `tr-<name>/_form.php` partial; and a JS function `edit<Name>Tr(lang, id, isDefault)` in `backend/web/js/product_view.js` that fetches the partial and injects it into `#tr_<name>`
- **Per-metal API fetch** — `MetalPricesController::actionFetchLatest` and `updateExchange.php` both pull all 4 metals (XAU, XAG, XPT, XPD) in one API call and write one `metal_price_real` row per metal_id
- **Locale-URL POST gotcha** — `codemix/yii2-localeurls` 302-redirects `/agora/x` → `/<lang>/agora/x`. Browsers drop POST bodies on 302. Backend + frontend now inject `window.AGORA_URLS` / `window.AGORA_ADMIN_URLS` from PHP with the language already in the path so fetch hits the right URL on the first try.
- **JS cache-busting** — `registerJsFile()` on bare paths does NOT respect `appendTimestamp` (only asset-bundle pipeline does). For directly-registered JS files (`/js/agora.js`, `/js/agora-admin.js`), the view manually appends `?v=<filemtime>`.

---

## Services and Ports

| Service    | URL                      | Notes                         |
|------------|--------------------------|-------------------------------|
| Frontend   | http://localhost:8080    | Customer-facing site          |
| Backend    | http://localhost:8090    | Admin panel                   |
| phpMyAdmin | http://localhost:8081    | root / `rootpassword`         |
| MariaDB    | localhost:3306           | host-side direct access       |
| Frontend   | http://goldmember.local  | host Apache reverse-proxy → :8080 |
| Backend    | http://admin.goldmember.local | host Apache reverse-proxy → :8090 |

The `*.goldmember.local` names need a one-time host setup (adds `/etc/hosts` entries + enables the proxy vhost in the host's Apache, which already owns port 80):
```bash
sudo bash docker/host-apache/setup-local-hosts.sh
```

---

## Database

| Setting  | Value                  |
|----------|------------------------|
| Host     | `db` (Docker) / `localhost` (host) |
| Database | `gssamru_goldmember`   |
| User     | `gssamru_autoimport`   |
| Password | `bGtVSaf2rv6hLWN`      |
| Root pw  | `rootpassword`         |

### Key Tables

| Table | Purpose |
|-------|---------|
| `product` | Gold/jewelry products (fineness, weight, karat, price) |
| `category` | Product categories |
| `product_image` | Product images |
| `tr_product` | Translated product fields per language (pattern repeats for news, blog, slider, partners, aboutus, pages, category, material, attribute, homepage, power_of_penny, news_category) |
| `news` / `tr_news` | News/blog articles with translations |
| `metal_prices` | Per-karat sell/buy/original prices. Upsert key: (metal_id, currency_id, karat, DATE(created_at)) |
| `metal_price_real` | Raw API data; one row per metal_id per fetch. Latest row per metal_id is what the admin form reads |
| `exchange_rates` | AMD-per-X rates (USD/EUR/RUB), used to convert metal prices to non-USD currencies |
| `user` | Admin + frontend users (role 0 = admin, 1 = broker, 20 = customer) |
| `customer` | Extended customer profile linked to user via `user_id` |
| `auction` | Auction entries. `lot_number` unique (auto-generated `GM-YYYY-NNN`). Pricing: `start_price`, `bid_increment` (default 50), `current_price` (cached high). Closing: `winner_user_id`, `winning_bid_id`, `winning_amount`, `closed_at` |
| `bid` | Live bid records per auction. `(auction_id, user_id, amount, created_at)` with index `(auction_id, id DESC)` |
| `donation_info` | Donations submitted via `/power-of-pany` |
| `donate` | Bank-account list shown on the donate page |
| `chat_usage` | Per-user chat tracking: (user_id, ip, message, tokens_in, tokens_out, created_at). Indexed on (user_id, created_at) for the daily-quota query |
| `language` | Language records (`id=3, short_code=am` default; `id=2, short_code=en`) |
| `user_activity` | Per-session visitor tracking |
| `session` | Yii2 DB sessions (frontend uses DbSession) |
| `team` | Team members (is_director=1 → homepage director quote) |
| `slider`, `sitesettings`, `social_net` | Standard CMS content |

**Connect directly:**
```bash
docker compose exec db mariadb -u gssamru_autoimport -pbGtVSaf2rv6hLWN gssamru_goldmember
```

---

## Credentials

### Admin (backend)
Local URLs: http://localhost:8090 or http://admin.goldmember.local (frontend: http://goldmember.local).

| Field    | Value    |
|----------|----------|
| Username | `harut`  |
| Password | `admin123` |
| Role     | 0 (admin) |
| User ID  | 18 |

Second local-only admin (created 2026-10-15, local Docker DB only — not on production): `admin` / `admin`, role 0, user ID 48.

### Test customer (frontend)
Inserted for Playwright tests; reuse for manual testing too.
| Field    | Value    |
|----------|----------|
| Username | `e2etester` |
| Password | `test123` |
| Email    | `e2e@test.local` |
| Role     | 20 (customer) |
| User ID  | 47 |

---

## Environment Variables

Local secrets live in `/var/www/html/goldmember/.env` (gitignored). See `.env.example` for the template.

| Variable | Used by | Notes |
|---|---|---|
| `CHATBOT_API_KEY` | `frontend/services/ChatBotService.php` via `params.php` → `getenv()` | OpenAI Responses API key. Empty = chat returns 503. |
| `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`, `SMTP_ENCRYPTION` | `common/components/Mailer.php` | Optional. Without `SMTP_HOST`, mail goes to `*/runtime/mail/*.eml`. |
| `METALPRICE_API_KEY` | `updateExchange.php` | Optional override of the built-in metalpriceapi key. |

After editing `.env`:
```bash
docker compose up -d   # picks up new env values
```

Apache `PassEnv` in `docker/apache/{frontend,backend}.conf` forwards the var to mod_php so `getenv()` works.

---

## Auction Flow

1. Admin creates a Product with `is_auction = 1`.
2. Admin → Auction → Create. Lot number auto-generated as `GM-YYYY-NNN` (configurable in `Auction::nextLotNumber`). DB has a unique index on `lot_number`.
3. After create the admin lands on the update page (not view) so they can immediately tweak settings + start an Agora stream.
4. Status badge on the form:
   - `upcoming` — current time < start_date
   - `live` — start_date ≤ now ≤ end_date
   - `ended` — now > end_date OR `closed_at IS NOT NULL`
5. Frontend customer at `/auction` sees the list; `/auction/{id}` (auth required) shows the detail page with countdown / live stream / ended message + bid panel.

### Agora live streaming
- Backend `auction/get-token` mints `ROLE_PUBLISHER` tokens (admin role=0 only). Frontend `agora/get-token` ALSO issues `ROLE_PUBLISHER` tokens now — viewers join as `host` role (so they're visible in `user-joined`) but don't publish anything until they click *Share my camera*. Subscriber-role tokens are NOT used anywhere.
- Frontend `agora/host-info?channel=auction-N` returns the auction creator's username — audience UI uses it for the "Waiting for `<host>`…" placeholder and the host's card label.
- Frontend `agora/user-info?uids=1,2,3` + backend `auction/user-info?uids=…` return `{uid, name, initial, color}` (HSL hue = `uid * 47 mod 360`) — used to render circular initials avatars in the participants list on both sides.
- Host's video plays ONLY in the main remote-player div (passing the `<div>` to `track.play()`, never a `<video>` element). The host's card in the participants list shows only their initials avatar plus a blinking red "Broadcasting" indicator (`markHostCardLive` / `markHostCardOffline`).
- Non-host publishers (viewers who click *Share my camera*) get their video tile mounted inside their card's circular slot via `mountVideoInCard` / `unmountVideoInCard`.
- Admin can broadcast `{type:'mute-all'}` / `{type:'unmute-all'}` via `client.sendStreamMessage`. Audience JS listens and mutes/unmutes own audio track only if the sender UID matches the host.
- JS event listeners (`user-joined`, `user-published`, `stream-message`) are attached **before** `client.join()` so events from already-connected users aren't missed; an explicit post-join sweep of `client.remoteUsers` backfills any active publishers.

### Bidding (Copart-style)
- Live auction page (`frontend/views/product/auction-detail.php`) shows a bid panel with: Current Bid + Next Minimum (in the dark gold header), big yellow `Bid ֏X` quick-button auto-filled with `next_minimum`, custom-amount input + Place button, "Recent bids" feed with yellow flash on each new entry.
- POST `/<lang>/agora/place-bid` (`AgoraController::actionPlaceBid`) — auth required, validates auction is live, host can't bid in their own auction, `amount ≥ next_minimum`. Saves bid + updates `auction.current_price`. After server save, bidder broadcasts `{type:'bid', bid, snapshot}` via Agora `sendStreamMessage`. 15-second polling on both viewer and admin sides is the safety net.
- GET `/<lang>/agora/bids?auction_id=N` and `/<lang>/auction/bids?auction_id=N` (admin) return the same snapshot shape via `Bid::snapshot()`.
- `common/models/Bid.php` — single source of truth. `snapshot()` computes `current_price` from `MAX(bid.amount)`, treats auction as closed if `closed_at IS NOT NULL` OR `end_date <= now`, falls back to highest bid as `winner` when the new auction columns aren't populated. `safeAttr()` helper returns a default for missing columns so the feature works pre-ALTER too.

### Finishing the auction
- Admin's auction form shows the bid panel for **every** status (live/upcoming/ended). When ended, the streaming UI is replaced with a small "live streaming no longer available" note, and the bid panel becomes read-only history.
- Live state has two close paths:
  - **Finish Auction (Sell to highest)** — big red button below the bid feed → POST `/auction/close-auction` with no `bid_id` → server picks highest.
  - **✓ Sell** — per-bid green button → POST `/auction/close-auction` with `bid_id=N` → server uses that specific bid as winner.
- `AuctionController::actionCloseAuction` (admin role=0 only): sets `closed_at`, `winner_user_id`, `winning_bid_id`, `winning_amount`, `current_price`, and pulls `end_date` to NOW so public status flips to ended. Idempotent (409 on double-close). Returns snapshot with winner spliced in from the `$bid` row directly, so the broadcast carries a winner even if the persistence columns are missing.
- After close, admin broadcasts `{type:'auction-ended', snapshot}` → all clients call `renderAuctionClosed` → viewer sees the 🔨 SOLD banner + "Won by X for ֏Y" (`hammerDrop` CSS animation); admin sees the gold "Sold to X" banner.

### View page (`/auction/view?id=N`)
- Linked from the View button on `/auction/index` (action column template now `{view}{update}{delete}`).
- Three sections: auction details (DetailView), 🔨 **Winner panel** with gold border + initials avatar + name/email/phone + big gold winning bid, and full **Bid History** table with the winning row highlighted (gold left-border, gold background, 🔨 icon, "Winner" tag).

---

## Chat (OpenAI)

- **Endpoint**: `frontend/controllers/ChatController::actionAsk` (POST, CSRF-protected, login required).
- **Service**: `frontend/services/ChatBotService` → OpenAI Responses API (`gpt-4.1-mini`).
- **Scope**: System prompt restricts answers to gold/metals/auctions/exchanges/products on this site. Off-topic questions get a one-sentence polite refusal.
- **Rate limit**: configurable in `frontend/config/params.php`:
  - `chatbotDailyLimit` → 50 messages/user/day (set to `0` to disable)
  - `chatbotMaxPromptLength` → 800 chars
- **Tracking**: every successful call logged in `chat_usage` with token counts.

---

## Docker Day-to-Day

```bash
# Start / stop
docker compose up -d
docker compose down

# Rebuild after Dockerfile / Apache vhost change
docker compose up -d --build

# Re-import DB (destructive — wipes current data)
docker compose down -v && docker compose up --build

# Shell into app container
docker compose exec app bash

# Logs
docker compose logs -f app
```

---

## Running Tests

### Playwright E2E (Python)

```bash
cd /var/www/html/goldmember
python3 -m pytest tests/playwright/ -v --browser chromium
```

| File | Covers |
|---|---|
| `test_frontend_homepage.py` | Homepage loads, hero, stats, no PHP errors, language switch |
| `test_frontend_products.py` | Product listing, auction, best-offer pages |
| `test_frontend_auth.py` | Login, register, forgot-password pages |
| `test_backend_auth.py` | Admin login (success + failure), auth redirect |
| `test_backend_crud.py` | Admin CRUD pages: products, news, categories, sliders, users |
| `test_backend_access_control.py` | Guests redirected from admin; customer accounts can't log into admin |
| `test_smoke_all_pages.py` | Every sitemap URL + account pages (customer) and every admin section + first update/view (admin): no 5xx / PHP errors. ~5 min |

Local-only tests (gitignored; written for ad-hoc validation, see `.gitignore`):
- `test_auction_flow.py` — auction CRUD + lot auto-gen + frontend listing/detail
- `test_chat_scope_and_limit.py` — guest blocked, CSRF required, oversized rejected
- `test_donate_and_users.py` — donate form persists, users mgmt menu + index

---

## Known Bugs Fixed

### Session 2026-10-15/16 (audit + 4 sprints)
See [`docs/PROGRESS-2026-10-16.md`](docs/PROGRESS-2026-10-16.md). Highlights: admin panel was open to any
customer account; `site/login-user` let anyone log in as any user by email; public blog delete; empty homepage
price tables + fake "Change" column; email broken on PHP 8.2; frontend wrote no error log; Armenian UI was
Latin transliteration. All fixed on the sprint branches.

### Earlier sessions
- Frontend crash: `language`/`sourceLanguage` were inside `components` block in `frontend/config/main.php` — moved to top-level
- Product listing crash: `frontend/views/product/index.php` had auction detail code; replaced with proper product grid

### Session 2026-05-22 (admin standardization + auction/chat/users)
- **`homepage/_form.php` 500** — `$defoultId` typo, only set inside `if (is_default)` foreach. Initialised before the loop.
- **`blog/create` 405** — `BlogController::behaviors()` had `'create' => ['POST']`; now allows GET+POST.
- **`editNewsTr` / `editMaterialTr` / `editHomePageTr` / `editPowerOfPennyTr` undefined** — all called from views but never defined. Added to `backend/web/js/product_view.js`.
- **TrMaterial / TrPowerOfPenny / TrNewsCategory** — controllers were pure Gii scaffold; rewrote `actionUpdate` to the AJAX pattern (TrNews shape) so the language flag tabs actually load forms.
- **`news-category/_form.php`** called `editCategoryTr` against `tr-category/update` — wrong endpoint when the id didn't exist in `category`. Now calls `editNewsCategoryTr` against `tr-news-category/update`. New `TrNewsCategoryController` + `tr-news-category/_form.php` partial added.
- **`metal-prices/index.php`** displayed Amharic dates (ICU treats `am` as Amharic). Backend formatter component now pinned to `en-US` in `backend/config/main.php`.
- **`metal-prices/create` page** — full rewrite:
  - Was: 13-column-wide bootstrap grid (broke), duplicate hidden-label artifacts, full page reload on metal change, `die;`-debug on save error, no transaction, no upsert, no currency conversion, gold-only purities for all metals
  - Now: responsive table, metal-specific purity sets (`Exchange::getPuritiesByMetal`), AJAX refresh on metal/currency change, transaction-wrapped upsert by (metal_id, currency_id, karat, today), flash messages, currency conversion via `exchange_rates`, "Fetch latest" button calls `actionFetchLatest` to refresh all 4 metals
- **`updateExchange.php`** — rewritten cron script. Now fetches all 4 metals in one API call. Reads DB credentials from env vars (defaults to localhost; set `DB_HOST=db` when running inside the compose network).
- **Auction model + controller** — auto-generated unique `lot_number` in `Auction::beforeSave()`; unique DB index on `lot_number`; validation rules (dates required, end > start); `actionCreate` redirects to `update` after save; auto-fills `user_id` from current admin.
- **Frontend layout null crash** — `frontend/views/layouts/main.php:363` accessed `Yii::$app->user->identity->customer->name` directly; killed every page for users without a Customer record. Now falls back to `username`.
- **Frontend home `index.php`** — assumed `metal_price_real.request_data` was an array; it's a JSON string. Now json_decoded with safety check.
- **Auction listing cards** — favorite heart and Live/Upcoming/Ended badge overlapped in the top-right; heart moved to top-left.
- **Auction detail Agora UX** — new `/agora/host-info` endpoint surfaces the auction creator's username; the audience UI now shows "Waiting for `<host>` to start the stream…" and labels the host correctly when they publish. Backend `actionGetToken` validates channel format and refuses non-admin role.
- **Chat hardening** — controller requires login (401), CSRF enforced (was bypassed), `die;`-debug removed; system prompt restricts to platform topics; daily quota per user via `chat_usage`; XSS fixed in `chat.php` (escape HTML on render); `.env`-based key with Apache `PassEnv` wiring.
- **Donate form** — `message` textarea had `name="message"` (not `DonateForm[message]`) so the field never reached the model; fixed via `$form->field()`. Page bottom padding added so the chat widget doesn't overlap the submit button.
- **`Class backend\controllers\Yii not found`** — 7 controllers used `Yii::t(...)` without `use Yii;`. Patched: DonateController, ExchangeRatesController, MetalsController, PartnersController, CurrenciesController, MetalPricesController, UserActivityController.
- **`frontend/config/news-routes.json` permission denied** — directory wasn't writable by `www-data` (uid 33). Fixed permissions on the file + parent.

### Session 2026-05-24 (product detail, tz, Agora overhaul, bidding, finish auction)
- **Product detail 404** — listing template linked to `Url::to(['/product/product', 'id' => $id])` but `ProductController::actionProduct` doesn't exist. Added URL rule `product/<id:\d+>` → `product/detail`, new `actionDetail($id)`, new `frontend/views/product/detail.php` styled like best-offer-detail but richer. `actionIndex` now redirects to detail when `?id=N` is passed. Listing + favorites templates updated to use the new route.
- **Auction countdown off by 4h** — server in UTC, admin enters datetimes in Yerevan local; `strtotime()` interpreted DB string as UTC. Added `'timeZone' => 'Asia/Yerevan'` to both `frontend/config/main.php` and `backend/config/main.php`. Yii's `setTimeZone` also calls PHP `date_default_timezone_set()`.
- **Audience main player black, host face in tiny avatar** — `playMainVideo` and `mountVideoInCard` were both being called for the host. Agora's `track.play()` can only render to one container at a time, so the second call moved the video out of the main player. Now the host's video plays in the main player only; their card shows a blinking "Broadcasting" indicator (`markHostCardLive` / `markHostCardOffline`). Non-host publishers (viewers sharing camera) still get the card-slot mount via `mountVideoInCard`.
- **Audience couldn't publish** — frontend tokens were `ROLE_SUBSCRIBER`; Agora's server rejects `publish()` from subscriber tokens regardless of client-side `setClientRole`. Switched `AgoraController::actionGetToken` to `ROLE_PUBLISHER`. Viewers join as `host` role by default so they're visible to everyone via `user-joined` (in `live` mode, audience is invisible).
- **Admin not seeing viewers in cards** — listeners attached AFTER `join()` were missing events from viewers already in the channel. Now attached BEFORE `join()`, plus an explicit `agoraClient.remoteUsers` sweep after join. Also: `registerJsFile()` on bare URLs doesn't get `appendTimestamp`; manually append `?v=<filemtime>` for `/js/agora.js` + `/js/agora-admin.js`. Confirmed via fresh-fetch HTML inspection.
- **`/agora/place-bid` got "Auction not found"** — `codemix/yii2-localeurls` 302-redirects bare `/agora/place-bid` → `/<lang>/agora/place-bid`; browsers turn POST into GET on 302 and drop the body. Both apps now inject `window.AGORA_URLS` / `window.AGORA_ADMIN_URLS` from PHP with the language prefix already in the path.
- **`UnknownPropertyException: Auction::current_price`** — production schema cache didn't have the new columns when code accessed them as ActiveRecord attributes. Added `Bid::safeAttr($auction, $col, $default)` everywhere we touch `bid_increment`, `current_price`, `winner_user_id`, etc. Feature works fully even before the ALTER lands.
- **Close button disappears after closing** — old `_form.php` replaced the entire bid panel with a 1-line alert for ended auctions, so admin couldn't see history or click anything. Now `_agora_host.php` takes `$auctionStatus` and hides only the streaming portion when ended; the bid panel and winner banner stay visible. Renamed button "Close Auction" → "Finish Auction (Sell to highest)" per owner phrasing.
- **"Auction closed — no winner" despite a sold bid** — same column-missing issue; `Bid::snapshot()` couldn't recall the winner because `winner_user_id` column was absent. Fixed in two layers: (1) snapshot now treats auction as closed if `end_date <= now` OR `closed_at IS NOT NULL`, and falls back to the highest bid as winner if columns missing. (2) `actionCloseAuction` post-processes the snapshot to splice the winner from the `$bid` row directly, so the immediate broadcast always carries a real winner.

---

## Admin standardization (this session)

Every admin section index page now follows the **product/index.php canonical pattern**:
- `<div class="table-layout"><div class="tray tray-center">` wrapper
- Green `+ Create X` button with `btn-system mb15`
- Panel wrap (`panel > panel-body pn > table table-responsive`)
- GridView with `tableOptions.class = 'table admin-form theme-warning tc-checkbox-1 fs13'`, pager-items-pager layout, sortable headers, action column with blue Update / red Delete buttons (`btn-info` / `btn-danger`, `btn-xs fs12 br2 ml5`)
- Filter rows / `_search` partials / Pjax wrappers removed

Sections covered: **product, news, blog, slider, partners, power-of-penny, donate (admin), news-category, news, aboutus, pages, category, material, attribute, auction, homepage, team, donate/donation-info, metals, currencies, metal-prices, exchange-rates, customer, user-activity, source-message, language, media, user (new Users Management page)**.

### Admin sidebar
New entries added during this session:
- **Users Management** → `/user/index` — lists all admin/broker/customer users with role badges, sortable by id desc, 30/page, view/edit/delete. The old `UserSearch` default `where(['role'=>1])` was removed.

### Translation flow (verified end-to-end)
| Section | JS function | AJAX endpoint | Container id |
|---|---|---|---|
| product | editProductTr | /tr-product/update | (per-language `tab_<id>`) |
| news | editNewsTr | /tr-news/update | `#tr_news` |
| blog | editBlogTr | /tr-blog/update | `#tr_blog` |
| slider | editSliderTr | /tr-slider/update | `#tr_slider` |
| partners | editPartnersTr | /tr-partners/update | `#tr_partners` |
| aboutus | editAboutTr | /tr-aboutus/update | `#tr_aboutus` |
| pages | editPagesTr | /tr-pages/update | `#tr_pages` |
| category | editCategoryTr | /tr-category/update | `#tr_category` |
| material | editMaterialTr | /tr-material/update | `#tr_material` |
| attribute | editAttributeTr | /tr-attribute/update | `#tr_attribute` |
| homepage | editHomePageTr | /tr-homepage/update | `#tr_homepage` |
| power-of-penny | editPowerOfPennyTr | /tr-power-of-penny/update | `#tr_powerofpenny` |
| news-category | editNewsCategoryTr | /tr-news-category/update | `#tr_newscategory` |

---

## Known Remaining Issues / Plan

The up-to-date list (what's done, what's left, deploy checklist) is in
[`docs/PROGRESS-2026-10-16.md`](docs/PROGRESS-2026-10-16.md). Short version of what's left:

1. **Merge into `main` + deploy** the sprint branches (pushing works again: `sprint-4-seo-i18n-perf` is on origin).
2. **Production-only config** (gitignored, check over FTP): disable Gii/debug in `backend/config/main-local.php`, `YII_DEBUG` off in both `web/index.php`, https `adminUrl` in `common/config/params.php`, cPanel cron for `updateExchange.php`, SMTP.
3. **Cart → checkout → payment** (Idram/ArCa) — needs owner decision + merchant account.
4. Migrate swiftmailer → `yii2-symfonymailer` (needs composer).
5. Native-speaker review of Armenian translations.
6. Data cleanup on production (junk news categories, "asdfasdf" blog row); USD exchange-rate row has buy > sell (the form now rejects that on save).

### Conventions added 2026-10-16
- **DB changes** go in `database/updates/YYYY-MM-DD-*.sql`, idempotent (see its README). No Yii console app exists.
- **Admin access** is enforced app-wide by `backend/components/AdminAccessControl` (attached in tracked `backend/config/bootstrap.php`); don't rely on per-controller rules alone.
- **Never probe delete/write endpoints with real ids**, even after removing them (opcache once served the old code and deleted a row).
- `backend/config/main.php`, `*/config/main-local.php`, `common/config/params.php`, `*/web/index.php` are **gitignored and differ per server** — put cross-environment code in tracked files (`bootstrap.php`, components).
- Changed CSS files pulled in via `@import` from `global.css` need a `?v=` bump there (cached 30 days).

---

## Production Deployment

The production server is **shared FTP hosting only** — port 22 closed, port 21 open. The FTP login lands directly in the Yii project root, so remote paths mirror local exactly (`frontend/`, `backend/`, `common/`, etc.). Apache rewrites map the document root to `frontend/web/`.

| Setting | Value |
|---|---|
| Host | `31.22.4.234` (= `goldmember.am`) |
| FTP user | `goldmember@goldmember.am` (cPanel-style virtual user) |
| Password | Provided per-session by owner — not committed anywhere |
| Tool | `lftp` (handles `put -O <remote_dir>` precisely; never `mirror -R`) |

**Standard "sync changed files" pattern** — list git-modified files, then run an `lftp` script with one `put -O <remote_dir> <local_path>` per file. Verify with `cls -l` and compare byte sizes to `stat -c%s` locally.

```bash
lftp -u "$USER","$PASS" "$HOST" <<'LFTP'
set ssl:verify-certificate no
set net:timeout 30
set xfer:clobber yes
set ftp:ssl-allow no
put -O <remote_dir>  <local_file>
cls -l <each_uploaded_file>
LFTP
```

**DB changes**: no remote `mysql` CLI access — owner runs SQL through their hosting control panel's phpMyAdmin. Write SQL with `ADD COLUMN IF NOT EXISTS` so it's idempotent.

**After schema changes**, clear Yii's table-schema cache (delete `runtime/cache/*` in both `frontend/` and `backend/` via FTP) or new columns won't be visible to ActiveRecord until the cache expires.

**Sensitive remote files** — do NOT overwrite blindly:
- `.env` (production secrets, including the real `CHATBOT_API_KEY`)
- `common/config/db-local.php` (production DB credentials)
- `frontend/web/.htaccess`, `backend/web/.htaccess`
- The 50KB root `index.php` (legacy fallback, leave alone)

**Live URLs to smoke-test after deploy:**
- https://goldmember.am/
- https://goldmember.am/am/auction
- https://goldmember.am/am/auction/<id> (auth required)
- https://goldmember.am/am/product/<id>
- https://admin.goldmember.am/

---

## Debugging Protocol

```bash
# Frontend app log
docker compose exec app cat /var/www/html/goldmember/frontend/runtime/logs/app.log 2>/dev/null | tail -30

# Backend app log
docker compose exec app cat /var/www/html/goldmember/backend/runtime/logs/app.log 2>/dev/null | tail -30

# Direct DB query
docker compose exec db mariadb -u gssamru_autoimport -pbGtVSaf2rv6hLWN gssamru_goldmember -e "SELECT ..."

# Clear Yii runtime cache
docker compose exec app rm -rf /var/www/html/goldmember/frontend/runtime/cache/*
docker compose exec app rm -rf /var/www/html/goldmember/backend/runtime/cache/*

# PHP syntax check (entire project)
docker compose exec app find /var/www/html/goldmember/frontend /var/www/html/goldmember/backend /var/www/html/goldmember/common -name "*.php" | xargs -I{} php -l {} 2>&1 | grep -v "No syntax errors"

# Toggle YII_DEBUG for verbose error pages (frontend)
# Edit frontend/web/index.php — first 2 lines:
#   defined('YII_DEBUG') or define('YII_DEBUG', true);
#   defined('YII_ENV') or define('YII_ENV', 'dev');
```

---

## Related Projects (same remote server)

| Project | Path | What it is |
|---------|------|-----------|
| SAP OCI PunchOut | `/var/www/html/sap/` | CS-Cart addon: SAP OCI 4.0 PunchOut integration |
| Nested Options | `/var/www/html/hierarchical/` | CS-Cart addon: hierarchical product options |

Both deploy to `test2.gaseus.es` (IP `113.30.148.239`, SSH as root).

---

## External Info

- **Live domain:** goldmember.am
- **Admin email:** info@goldmember.am
- **Phone:** (+374) 94261606, (+374) 43261605
- **Social:** Facebook, TikTok, YouTube, Instagram, WhatsApp (all active)
- **Director:** Albert Papikyan (team record id=6, `is_director=1`)
