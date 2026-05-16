# Goldmember — Improvement Backlog

Priority scale: **P0** = blocking / critical, **P1** = high value, **P2** = improvement, **P3** = cleanup/polish

---

## P0 — Critical / Must Fix

### Complete cart → order flow
**Problem:** `CartController` handles only the `Service` model. Products (gold jewelry) cannot be purchased. No `Order` record is created for product purchases.  
**What to build:**
- Wire `Product` model into `CartController::actionAdd()` / `actionList()` / `actionRemove()`
- Create or verify `Order::save()` stores order with customer, product list, address, total
- Add order confirmation view
**Files to touch:** `frontend/controllers/CartController.php`, `frontend/models/Order.php`, `frontend/views/cart/`

---

### Secure `cookieValidationKey`
**Problem:** `frontend/config/main.php` has `cookieValidationKey => '!SomeRandomString@'` — a placeholder known to anyone who reads this repo.  
**Fix:** Read from environment variable: `getenv('COOKIE_VALIDATION_KEY') ?: 'dev-fallback-key'`  
**File:** `frontend/config/main.php`

---

### Add null guard for `$director` on homepage
**Problem:** `frontend/views/site/index.php` line 42: `$directorImage = ... $director->id ...` crashes if no team member has `is_director=1`.  
**Fix:** Add `if ($director): ... endif;` around the director section, or use `$director?->id ?? null`.  
**File:** `frontend/views/site/index.php`

---

## P1 — High Value

### Scheduled metal price auto-fetch
**Problem:** `metal_price_real` data is stale (last updated June 2025). The `updateExchange.php` script exists but is not scheduled.  
**What to build:**
- Yii2 console command: `backend/commands/ExchangeJobController.php` likely already exists — verify + test
- Set up cron: `0 */6 * * * docker compose exec app php yii exchange-job/fetch`
- Or add a docker-compose scheduled service
**Files:** `backend/commands/ExchangeJobController.php`

---

### Payment gateway integration
**Problem:** No way for customers to pay for purchased products.  
**What to build:**
- Integrate ARCA (Armenian Card) or IDram (Armenian payment) for AMD transactions
- Add payment step between order confirmation and completion
- Webhook handler for payment status updates
**Files:** New `frontend/controllers/PaymentController.php`

---

### Email notifications
**Problem:** SwiftMailer is configured for file transport (`useFileTransport = true`). No emails are actually sent.  
**What to build:**
- Configure SMTP in `common/config/main-local.php`
- Trigger emails on: order placement, auction win, password reset (already has template)
**Files:** `common/config/main.php`, `common/mail/`

---

### Real admin dashboard
**Problem:** Backend "Dashboard" redirects to site settings page. No real KPIs are shown.  
**What to build:** A proper dashboard with:
- Today's visits (from `user_activity` table)
- Active users (sessions last 5 min)
- New registrations this week
- Today's gold price change (from `metal_price_real`)
- Active auctions count
- Recent orders
**Files:** `backend/controllers/SiteController.php`, `backend/views/site/index.php`

---

### REST API for mobile app
**Rationale:** APNS + GCM push notification config exists in `common/config/main.php` → implies a mobile app. The app needs an API.  
**What to build:**
- `/api/v1/products` — product listing with filters
- `/api/v1/products/{id}` — product detail
- `/api/v1/metal-prices` — latest local + global prices
- `/api/v1/exchange-rates` — latest currency rates
- `/api/v1/auctions` — active auctions
- `/api/v1/auth/login` + `/api/v1/auth/logout`
**Files:** New `api/` Yii2 application (similar to `backend/` + `frontend/`)

---

## P2 — Improvements

### Product search and filtering
**Problem:** `/search` shows all products with no filter controls. The old car-marketplace filters (Make/Model/Year) were removed.  
**What to build:**
- Sidebar filter by: material (gold/silver/platinum), fineness (999/875/750/585), price range, category
- Sort by: price ASC/DESC, newest first
- Pagination (currently shows all 24 with limit)
**Files:** `frontend/views/product/index.php`, `frontend/controllers/ProductController.php`

---

### Auction bid placement
**Problem:** The frontend auction detail shows a "Join Stream" button but no bid input. Bids are not captured.  
**What to build:**
- Add bid input + "Place Bid" button to `auction-detail.php` for live auctions
- New `AuctionBid` model + table (`auction_bids`: id, auction_id, user_id, amount, created_at)
- AJAX endpoint to submit/validate bid
- Real-time bid update via Agora data channel or polling
**Files:** `frontend/views/product/auction-detail.php`, new `frontend/controllers/AuctionController.php`

---

### Product detail page verification
**Problem:** Product detail route (`/product/product?id=X` or `/{route_name}`) not tested end-to-end. The `product_new_view.php` handles this but may have null-pointer issues.  
**What to do:** Test `/en/product/product?id=10` and `/en/product/product?id=11`, verify images load, details show, "Add to favorites" works.  
**Files:** `frontend/views/product/product_new_view.php`

---

### PHPUnit unit tests
**Problem:** No unit tests exist. Only Playwright E2E tests were added.  
**What to build:**
- `tests/Unit/MetalPriceHelperTest.php` — test karat ratio calculations
- `tests/Unit/ExchangeRateFormatterTest.php` — test AMD formatting
- `tests/Unit/AuctionStatusTest.php` — test upcoming/live/ended logic
**Files:** New under `tests/` directory

---

### Real-time metal price widget on homepage
**Problem:** Homepage shows metal prices from today's `metal_prices` table. Prices are refreshed manually.  
**What to build:**
- JavaScript polling every 60s to `/api/v1/metal-prices`
- Update price cells in-place without full page reload
- Visual +/- indicator vs previous price

---

## P3 — Cleanup / Polish

### Remove backup and dead view files
The `frontend/views/product/` directory contains many backup files:
- `index-2024-10.php`, `index-old.php`, `index-old2.php`, `index-backup-2020.php`
- `basket-product-old.php`, `CartController-Old.php`
- `product-list-view.php`, `product-grid-view.php` (old auto-parts versions, no longer used)

These add confusion and clutter. Delete them.

---

### Internationalize backend admin panel
Backend admin views use Russian labels (e.g., "Обновить", "Обратно к списку", breadcrumbs in Russian). Standardize to English or add proper i18n support with `am` and `ru` translations.

---

### Fix frontend URL routing — clean up `main.php`
`frontend/config/main.php` has 20+ `array_push($Rules, ...)` calls in procedural style. Refactor to a clean declarative array. Also remove commented-out routing code.

---

### Add `robots.txt` and sitemap
The site is goldmember.am — a live production site. `robots.txt` doesn't exist. Add:
- `/frontend/web/robots.txt`
- Basic sitemap with news, product, category, auction URLs

---

## Infrastructure

### Add GitHub Actions CI/CD
Build a `.github/workflows/ci.yml` that:
1. Installs PHP + Composer
2. Runs PHP syntax check on all `.php` files
3. Runs Playwright tests against a Docker Compose environment
4. On push to `main`, deploy to production server

### Add health check endpoint
Add `/site/health` → `200 OK` with JSON `{status: "ok", db: "ok", version: "..."}`. Used by monitoring / load balancers.
