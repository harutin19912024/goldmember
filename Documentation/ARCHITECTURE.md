# Goldmember — Architecture

---

## System Overview

```
                    ┌─────────────────────────────────────────────┐
                    │              Docker Compose                   │
                    │                                               │
                    │  ┌──────────────┐     ┌──────────────────┐  │
                    │  │  goldmember  │     │  goldmember-db   │  │
Internet ──────────▶│  │  -app        │────▶│  MariaDB 10.11   │  │
                    │  │  PHP 8.2     │     │  gssamru_gold    │  │
                    │  │  Apache      │     │  member          │  │
                    │  │  :8080 (FE)  │     └──────────────────┘  │
                    │  │  :8090 (BE)  │                            │
                    │  └──────────────┘     ┌──────────────────┐  │
                    │                       │  goldmember-pma  │  │
                    │                       │  phpMyAdmin      │  │
                    │                       │  :8081           │  │
                    │                       └──────────────────┘  │
                    └─────────────────────────────────────────────┘
```

---

## Yii2 Application Structure

### Three-tier Advanced Template

```
/var/www/html/goldmember/
│
├── frontend/          ← Customer-facing website (port 8080)
│   ├── config/main.php        language=am, DbSession, localeurls
│   ├── controllers/           SiteController, ProductController, AgoraController, CartController
│   ├── models/               LoginForm (email→user), Product (extends common)
│   └── views/                layouts/, site/, product/
│
├── backend/           ← Admin panel (port 8090)
│   ├── config/main.php        language=am, file-based session
│   ├── controllers/           ProductController, NewsController, AuctionController,
│   │                          MetalPricesController, ExchangeRatesController, ...
│   ├── models/               Product, News, Auction, MetalPrices, ExchangeRates, ...
│   └── views/                layouts/, product/, news/, auction/, metal-prices/, ...
│
├── common/            ← Shared models and components
│   ├── models/               User, Language, MetalPriceReal, Currencies, Favorites, ...
│   ├── components/           VisitorTracker, CurrencyHelper, UserComponent, Notification
│   └── config/db-local.php   DB credentials
│
└── docker/            ← Docker configs
    ├── apache/{frontend,backend}.conf    Virtual hosts
    ├── config/db-local.php               Docker DB override
    ├── mysql/01-init.sh                  DB import on first start
    └── php/php.ini
```

---

## Key Data Flows

### Metal Prices → Frontend
```
Admin panel (/metal-prices/create or /update)
    → INSERT metal_prices (metal_id=1, karat, buy_price, sell_price, created_at)
    → Picked up by SiteController::actionMoreDetails()
    → Queried: most recent date batch, metal_id=1
    → Displayed in "Local Prices" table on /more-details
```

### Global API Price → Frontend
```
External API (metalpriceapi.com or FOREXCOM)
    → Stored in metal_price_real (JSON blob: bid, ask, ch, chp, price_gram_24k, ...)
    → SiteController::actionMoreDetails() reads latest row
    → Decodes JSON, computes per-karat prices from bid/ask
    → "Global Prices" table: 12 karats × buy/sell
    → "Global Spot Prices": gold price, +/- change, date
```

### Exchange Rates → Frontend
```
Admin panel (/exchange-rates/create or /update)
    → INSERT exchange_rates (currency_id, sell_rate, buy_rate, updated_at)
    → SiteController::actionMoreDetails() joins exchange_rates + currencies
    → "Exchange Rates" table: USD/EUR/RUB vs AMD
```

### Auction Live Stream
```
Admin creates auction (backend /auction/create)
    → auction record: product_id, start_date, end_date, lot_number, start_price
    → Channel auto-assigned: "auction-{id}"

Admin opens /auction/update?id={id}
    → Agora host panel visible (if status != ended)
    → Clicks "Join as Host"
    → JS: GET /auction/get-token?channel=auction-{id}
    → AuctionController::actionGetToken() → RtcTokenBuilder2 → ROLE_PUBLISHER token
    → agoraClient.setClientRole('host') → join → publish audio+video

Frontend user (logged in) views /auction/{id}
    → ProductController::actionAuction($auctionId)
    → Renders auction-detail.php with status=live
    → Agora viewer JS injected
    → User clicks "Join Stream"
    → JS: GET /agora/get-token?channel=auction-{id}
    → AgoraController::actionGetToken() → ROLE_SUBSCRIBER token
    → agoraClient.setClientRole('audience') → join → subscribe to host stream
```

### User Session (Frontend)
```
Frontend uses yii\web\DbSession → session table
    session: id(char40), user_id, last_write, expire, data

VisitorTracker (bootstrap component):
    → Every request: upsert session row with user_id + last_write
    → Powers "Online Now" counter on homepage
```

---

## Authentication

### Backend
- `backend\models\LoginForm` extends `common\models\LoginForm`
- `getUser()` → `backend\models\User::findByUsername($username)`
- Looks up by `username` field in `user` table
- Sessions: file-based (not DbSession)
- Access: role=0 (ADMIN constant) required for most controllers

### Frontend
- `frontend\models\LoginForm` extends `common\models\LoginForm`
- `getUser()` → `User::findByEmail($username)` first, then `findByUsername($username)` fallback
- `findByEmail`: looks up `customer.email` → gets `customer.user_id` → finds `user`
- Sessions: `yii\web\DbSession` → `session` table with `writeCallback` adding `user_id`

---

## Key Models

| Model | Table | Notes |
|-------|-------|-------|
| `common\models\User` | `user` | role=0 admin, role=20 customer |
| `common\models\Language` | `language` | id=2 en, id=3 am (default) |
| `backend\models\Product` | `product` | is_auction=1 for auction items |
| `backend\models\TrProduct` | `tr_product` | Translated titles/descriptions |
| `backend\models\News` | `news` | status=1 published |
| `backend\models\NewsImages` | `news_images` | default_image_id=1 = primary |
| `backend\models\Auction` | `auction` | video_link overrides Agora |
| `backend\models\MetalPrices` | `metal_prices` | metal_id=1 gold, karat, buy_price, sell_price |
| `common\models\MetalPriceReal` | `metal_price_real` | JSON API snapshot |
| `backend\models\ExchangeRates` | `exchange_rates` | currency_id → currencies.code |
| `frontend\models\Customer` | `customer` | Extended profile for frontend users |

---

## Agora Configuration

| Setting | Value |
|---------|-------|
| App ID | `36458fbe17d246eba77eae7e6ef6360f` |
| App Certificate | `b74eeb2d0cb948d6a03072f140d5eb10` |
| Channel format | `auction-{auction_id}` |
| Admin token role | `ROLE_PUBLISHER` (via `/auction/get-token`) |
| Viewer token role | `ROLE_SUBSCRIBER` (via `/agora/get-token`) |
| SDK files | `frontend/web/agora/`, `backend/web/agora/` |
| Admin JS | `backend/web/js/agora-admin.js` |
| Viewer JS | `frontend/web/js/agora.js` |

---

## Ports Summary

| Port | Service | URL |
|------|---------|-----|
| 8080 | Frontend (Customer) | http://localhost:8080 |
| 8090 | Backend (Admin) | http://localhost:8090 |
| 8081 | phpMyAdmin | http://localhost:8081 |
| 3306 | MariaDB | localhost:3306 |
