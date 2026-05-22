# Goldmember — Claude Context

> **Start every session by verifying Docker is running:**
> ```bash
> docker compose ps   # should show 3 containers: app, db, phpmyadmin
> docker compose up -d   # start if stopped
> ```

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
- **Session-based cart** — using `omnilight/yii2-shopping-cart` + manual session keys
- **VisitorTracker** — bootstrap component tracking every request in `user_activity` table
- **Multi-model translations** — pattern `Product` + `TrProduct`, `News` + `TrNews`, etc.
  - Each translatable section has: a `Tr<Name>Controller` with AJAX `actionUpdate` that returns the form partial; a `tr-<name>/_form.php` partial; and a JS function `edit<Name>Tr(lang, id, isDefault)` in `backend/web/js/product_view.js` that fetches the partial and injects it into `#tr_<name>`
- **Per-metal API fetch** — `MetalPricesController::actionFetchLatest` and `updateExchange.php` both pull all 4 metals (XAU, XAG, XPT, XPD) in one API call and write one `metal_price_real` row per metal_id

---

## Services and Ports

| Service    | URL                      | Notes                         |
|------------|--------------------------|-------------------------------|
| Frontend   | http://localhost:8080    | Customer-facing site          |
| Backend    | http://localhost:8090    | Admin panel                   |
| phpMyAdmin | http://localhost:8081    | root / `rootpassword`         |
| MariaDB    | localhost:3306           | host-side direct access       |

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
| `auction` | Auction entries (`lot_number` unique, auto-generated `GM-YYYY-NNN` in `Auction::beforeSave`); `start_price DECIMAL(12,2)` |
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
| Field    | Value    |
|----------|----------|
| Username | `harut`  |
| Password | `admin123` |
| Role     | 0 (admin) |
| User ID  | 18 |

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
   - `ended` — now > end_date
5. Frontend customer at `/auction` sees the list; `/auction/{id}` (auth required) shows the detail page with countdown / live stream / ended message.
6. Agora flow: backend `actionGetToken` mints publisher tokens (admin only, role=0); frontend `AgoraController::actionGetToken` mints subscriber tokens. Frontend `actionHostInfo` returns the auction creator's username so the audience UI can show "Waiting for `<host>` to start the stream…" and label them correctly once they publish.

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

Local-only tests (gitignored; written for ad-hoc validation, see `.gitignore`):
- `test_auction_flow.py` — auction CRUD + lot auto-gen + frontend listing/detail
- `test_chat_scope_and_limit.py` — guest blocked, CSRF required, oversized rejected
- `test_donate_and_users.py` — donate form persists, users mgmt menu + index

---

## Known Bugs Fixed

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

## Known Remaining Issues

| Issue | Location | Severity |
|---|---|---|
| `CartController` mixes `Service` and `Product` models | `frontend/controllers/CartController.php` | High — cart not functional for actual products |
| `cookieValidationKey` hardcoded placeholder | `frontend/config/main.php:63` | Medium — should move to env var |
| No payment gateway integration | — | Critical for marketplace |
| Backend admin panel string i18n incomplete (Russian/English mix) | various backend views | Low — cosmetic |
| Dozens of backup/dead view files (`*-old.php`, `*-backup-*`) | `backend/views/*` | Low — clutter |
| `enableStrictParsing=false` on frontend URL manager | `frontend/config/main.php` | Low — may route unintended 404s |
| Git remote URL embeds a GitHub PAT in `.git/config` | `.git/config` | High — anyone with shell access can extract; rotate + use credential helper |
| `/media/*` admin pages 500 with `Files::faxbid_or_vinradar` missing | pre-existing model bug | Low — needs investigation |

---

## Improvement Plan (priority order)

### P0 — Critical / Blocking
1. **Complete cart → order flow** — handle `Product` (not just `Service`) and create actual `Order` records
2. **Harden `cookieValidationKey`** — read from env var
3. **Rotate the embedded GitHub PAT** in the git remote URL

### P1 — High Value
4. **Schedule `updateExchange.php`** via cron so `metal_price_real` stays fresh
5. **Payment gateway** — integrate ARCA or IDram (Armenia) for purchases
6. **Email notifications** — configure SwiftMailer SMTP; send order confirmations, price alerts
7. **Admin dashboard** — replace placeholder with real KPIs (daily visits, new users, today's gold price delta)
8. **Implement bid placement + bid history** on auctions — currently the Agora stream is just video; there's no on-platform bid recording

### P2 — Improvements
9. **PHPUnit unit tests** for `CurrencyHelper`, `MetalPriceReal`, `Product::findList()`
10. **Product search filters** — fineness/karat, price range, material type
11. **REST API for mobile** — APNS/GCM suggests a mobile app; add `/api/v1/`
12. **Real-time price widget** — JS polling or WebSocket for live metal prices on homepage

### P3 — Cleanup
13. **Remove backup view files** — delete `*-old*`, `*-backup-*`, `*-Old*`
14. **Backend admin UI i18n** — standardize to English or finish translation

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
