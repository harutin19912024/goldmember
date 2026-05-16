# Goldmember — Claude Context

> **Start every session by verifying Docker is running:**
> ```bash
> docker compose ps   # should show 3 containers: app, db, phpmyadmin
> docker compose up -d   # start if stopped
> ```

---

## What This Project Is

**Goldmember** (`goldmember.am`) is a gold/precious-metals marketplace and trading platform targeting the Armenian market. It is built on **Yii2 Advanced Template** (PHP 8.2) with MariaDB, served via Apache inside Docker.

### Business Features
- Product catalog of gold/jewelry items with photos, pricing, fineness, weight
- Auction system for premium items
- Best Offer listings
- Real-time metal prices (gold, silver — by karat: 999/995/875/750/585)
- Currency exchange rates (AMD, USD, EUR, etc.)
- Customer registration, login, favorites
- Multi-language: Armenian (`am`, default) + English (`en`)
- News/blog section
- Push notifications (APNS + GCM configured — implies a mobile app)
- Donate / "Power of Penny" fundraising section
- Backend admin panel for all content management

---

## Architecture

```
/var/www/html/goldmember/
├── backend/          → Admin panel   (http://localhost:8090)
│   ├── controllers/  → 35+ controllers (Product, News, Slider, Exchange, etc.)
│   ├── models/       → ActiveRecord models (backend-specific)
│   ├── views/        → 300+ view files across 46 directories
│   └── web/          → entry point: index.php
│
├── frontend/         → Customer site  (http://localhost:8080)
│   ├── controllers/  → SiteController, ProductController, CartController, etc.
│   ├── models/       → Frontend-specific models
│   ├── views/        → 118 view files
│   └── web/          → entry point: index.php
│
├── common/           → Shared between frontend + backend
│   ├── models/       → User, Language, Product, Customer, MetalPriceReal, etc.
│   ├── components/   → VisitorTracker, CurrencyHelper, UserComponent, Notification
│   └── config/       → db-local.php, main.php, params.php
│
├── docker/           → Docker configs (Apache vhosts, PHP ini, MySQL init)
├── docker-compose.yml
├── Dockerfile        → PHP 8.2-apache
└── gssamru_goldmember.sql  → DB dump (import on first start)
```

### Key Design Choices
- **Yii2 Advanced Template** — backend and frontend are separate Yii2 apps sharing `common/`
- **codemix/yii2-localeurls** — URL language prefixes (`/am/...`, `/en/...`)
- **Session-based cart** — using `omnilight/yii2-shopping-cart` package + manual session keys
- **VisitorTracker** — bootstrap component tracking every request in `user_activity` table
- **Multi-model translations** — pattern `Product` + `TrProduct`, `News` + `TrNews`, etc.

---

## Services and Ports

| Service    | URL                      | Notes                         |
|------------|--------------------------|-------------------------------|
| Frontend   | http://localhost:8080    | Customer-facing site          |
| Backend    | http://localhost:8090    | Admin panel (Russian UI)      |
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
| `product_image` | Product images (default_image_id=1 = main) |
| `tr_product` | Translated product fields per language |
| `news` / `tr_news` | News/blog articles with translations |
| `metal_prices` | Price per karat + metal + currency (time-series) |
| `metal_price_real` | Real-time metal price data from external API |
| `exchange_rates` | Currency exchange rates (updated daily) |
| `user` | Admin + frontend users (role 0 = admin, 20 = customer) |
| `customer` | Extended customer profile linked to user |
| `auction` | Auction entries linked to products |
| `language` | Language records (`id=3, short_code=am` default; `id=2, short_code=en`) |
| `user_activity` | Per-session visitor tracking |
| `session` | Yii2 DB sessions (frontend uses DbSession) |
| `team` | Team members (is_director=1 → homepage director quote) |
| `slider` | Homepage slider images |
| `sitesettings` | Global site config (phone, email, meta, hours) |
| `social_net` | Social media links (Facebook, TikTok, Instagram, etc.) |

**Connect directly:**
```bash
docker compose exec db mariadb -u gssamru_autoimport -pbGtVSaf2rv6hLWN gssamru_goldmember
```

---

## Admin Credentials (local Docker)

| Field    | Value    |
|----------|----------|
| Username | `harut`  |
| Password | `admin123` |
| Role     | 0 (admin) |
| Email    | test@test.com |

---

## Docker Day-to-Day

```bash
# Start
docker compose up -d

# Stop
docker compose down

# Rebuild (after Dockerfile change)
docker compose up --build

# Re-import DB (destructive — wipes current data)
docker compose down -v && docker compose up --build

# Run Yii console command
docker compose exec app php yii <command>

# Shell into app container
docker compose exec app bash

# View logs
docker compose logs -f app
```

---

## Running Tests

### Playwright E2E (Python)

```bash
cd /var/www/html/goldmember

# Run all tests
python3 -m pytest tests/playwright/ -v --browser chromium

# Run a specific suite
python3 -m pytest tests/playwright/tests/test_frontend_homepage.py -v --browser chromium

# Headed mode (watch it run)
python3 -m pytest tests/playwright/ --headed -v --browser chromium
```

Test suites in `tests/playwright/tests/`:
| File | Covers |
|------|--------|
| `test_frontend_homepage.py` | Homepage loads, hero, stats, no PHP errors, language switch |
| `test_frontend_products.py` | Product listing, auction, best-offer pages |
| `test_frontend_auth.py` | Login, register, forgot-password pages |
| `test_backend_auth.py` | Admin login (success + failure), auth redirect |
| `test_backend_crud.py` | Admin CRUD pages: products, news, categories, sliders, users |

### No PHPUnit Tests Yet
PHPUnit is required in `composer.json` (via `yiisoft/yii2-codeception`) but no test files exist yet. See improvement plan for test tasks.

---

## Known Bugs Fixed

| Bug | Fix |
|-----|-----|
| Frontend homepage crashes with "Attempt to read property 'id' on null" | `language` and `sourceLanguage` were inside `components` block in `frontend/config/main.php` — moved to top-level application config |

---

## Known Remaining Issues

| Issue | Location | Severity |
|-------|----------|----------|
| No null-check on `$director` in homepage view | `frontend/views/site/index.php:42` | Medium — crashes if no team member marked `is_director=1` |
| Backend admin panel UI is Russian, code comments English | Various backend views | Low — cosmetic |
| `CartController` mixes `Service` and `Product` models | `frontend/controllers/CartController.php` | High — cart may not work end-to-end |
| Dozens of backup/dead view files (`index-old.php`, `*-backup-*`) | `backend/views/product/`, etc. | Low — clutter |
| `cookieValidationKey` is hardcoded placeholder | `frontend/config/main.php:63` | Medium — should be env var |
| No payment gateway integration | — | Critical for marketplace |
| Metal price updates are manual — no scheduled fetch | `updateExchange.php` exists but needs cron | High |
| `enableStrictParsing=false` on frontend URL manager | `frontend/config/main.php` | Low — may route 404s unintentionally |
| Duplicate `sourceLanguage` key in frontend config (old one at line 54) | `frontend/config/main.php` | Low |

---

## Improvement Plan (Senior Architect Priority Order)

### P0 — Critical / Blocking
1. **Fix null `$director` crash** — add `?->` or `isset()` guard in homepage view
2. **Complete cart → order flow** — CartController currently only handles `Service` model; needs to handle `Product` and create actual `Order` records
3. **Harden `cookieValidationKey`** — read from env var, not hardcoded

### P1 — High Value
4. **Metal price auto-fetch** — cron job or Yii2 console command to call external API and populate `metal_price_real`/`metal_prices` daily
5. **Payment gateway** — integrate a payment provider (e.g., ARCA, IDram for Armenia) for product purchases
6. **Email notifications** — configure SwiftMailer SMTP (currently set to file transport); send order confirmations, price alerts
7. **Admin dashboard metrics** — replace placeholder settings page with real KPIs: daily visits, new users, active auctions, today's gold price change

### P2 — Improvements
8. **PHPUnit tests** — at least unit tests for `CurrencyHelper`, `MetalPriceReal`, `Product::findList()`
9. **Product search improvements** — add filter by fineness/karat, price range, material type
10. **REST API for mobile** — APNS/GCM suggests a mobile app; add `/api/v1/` endpoints (products, prices, user auth)
11. **Real-time price widget** — JavaScript polling or WebSocket for live metal prices on the homepage

### P3 — Cleanup
12. **Remove backup view files** — delete `*-old*`, `*-backup-*`, `*-Old*` files
13. **Separate frontend route config** — move inline `array_push($Rules, ...)` to a clean JSON/PHP array
14. **Internationalize backend** — admin panel strings currently in Russian; either add i18n or standardize to English

---

## Debugging Protocol

1. **Check frontend error:**
   ```bash
   docker compose logs app 2>&1 | grep -i error | tail -20
   ```

2. **Check PHP error log inside container:**
   ```bash
   docker compose exec app cat /var/www/html/goldmember/backend/runtime/logs/app.log 2>/dev/null | tail -30
   docker compose exec app cat /var/www/html/goldmember/frontend/runtime/logs/app.log 2>/dev/null | tail -30
   ```

3. **Direct DB query:**
   ```bash
   docker compose exec db mariadb -u gssamru_autoimport -pbGtVSaf2rv6hLWN gssamru_goldmember -e "SELECT ..."
   ```

4. **Clear Yii runtime cache:**
   ```bash
   docker compose exec app rm -rf /var/www/html/goldmember/frontend/runtime/cache/*
   docker compose exec app rm -rf /var/www/html/goldmember/backend/runtime/cache/*
   ```

5. **Run PHP syntax check:**
   ```bash
   docker compose exec app find /var/www/html/goldmember/frontend /var/www/html/goldmember/backend /var/www/html/goldmember/common -name "*.php" | xargs -I{} php -l {} 2>&1 | grep -v "No syntax errors"
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
