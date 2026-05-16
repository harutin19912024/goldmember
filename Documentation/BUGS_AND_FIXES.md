# Goldmember — Bugs Found & Fixed

---

## Fixed Bugs

### BUG-01: Frontend crashes on homepage
**Symptom:** `PHP Warning – Attempt to read property "id" on null` at `frontend/views/site/index.php:33`  
**Root cause:** `language` and `sourceLanguage` were placed inside the `components` array in `frontend/config/main.php`. Yii2 ignores them there, so `Yii::$app->language` defaulted to `en-US`. `Language::find()->where(['short_code' => 'en-US'])->one()` returned null, causing `$language->id` to crash.  
**Fix:** Moved `language` and `sourceLanguage` to the top-level app config (outside `components`).  
**File:** `frontend/config/main.php`

---

### BUG-02: Product listing page crashes with "Unknown Method: getAllModels()"
**Symptom:** `/search` → `yii\base\UnknownMethodException: Calling unknown method frontend\models\Product::getAllModels()`  
**Root cause:** `frontend/views/product/index.php` was overwritten with an auto-parts marketplace view that called `getAllModels()`, `getAllMarks()`, `getAllYears()` — methods from a car marketplace that don't exist in the gold marketplace Product model.  
**Fix:** Replaced the entire `index.php` with a gold-appropriate Bootstrap product card grid.  
**File:** `frontend/views/product/index.php`

---

### BUG-03: Backend layout crashes when user not authenticated
**Symptom:** Accessing any unprotected backend route without login → `Attempt to read property "id" on null` at `backend/views/layouts/main.php:18`, then `"username" on null` at line 130.  
**Root cause:** Layout directly accessed `Yii::$app->user->identity->id` and `->username` without null checks. Two controllers (`ExchangeRatesController`, `MetalPricesController`) had no `AccessControl` — so unauthenticated requests reached the layout.  
**Fix:**  
1. Added null-safe checks in `backend/views/layouts/main.php` (`->id ?? null`, `->username ?? 'Guest'`).  
2. Added `AccessControl` to both controllers.  
**Files:** `backend/views/layouts/main.php`, `backend/controllers/ExchangeRatesController.php`, `backend/controllers/MetalPricesController.php`

---

### BUG-04: "More Details" / Metals Prices page — 3 empty sections + wrong subtitle
**Symptoms:**  
- "Local Prices" table empty  
- "Global Spot Prices" table empty  
- "PricesText" shown as literal text  
- 390px blank gap between tables and TradingView chart  

**Root causes:**  
- Local Prices: query filtered `WHERE created_at BETWEEN today 00:00 AND today 23:59`. All data is from Sep 2025. No data today → empty table.  
- Global Spot Prices: `$data` and `$dataYesterday` variables relied on API calls that were commented out. Never set → `if(!empty($data['rates']))` always false.  
- "PricesText": `Yii::t('app', 'PricesText')` — key `PricesText` not in translation table → returns the key literally.  
- 390px gap: hardcoded `style="margin-top: 390px;"` on TradingView iframe container.  
- Exchange Rates: never fetched or displayed on the page.  
- All data fetching was inside the view instead of the controller.  

**Fix:**  
- Moved all data fetching to `SiteController::actionMoreDetails()`.  
- Local Prices: fetch most-recent date's batch via `DATE(created_at)` subquery.  
- Global Spot Prices: reuse `MetalPriceReal` API snapshot already fetched for Global Prices.  
- Exchange Rates: add full section pulling from `exchange_rates` table with currency join.  
- Replaced "PricesText" with real descriptive text.  
- Removed the 390px margin.  
**Files:** `frontend/controllers/SiteController.php`, `frontend/views/site/moreDetails.php`

---

### BUG-05: `MetalPriceReal::request_data` — json_decode type mismatch
**Symptom:** `TypeError: json_decode(): Argument #1 ($json) must be of type string, array given`  
**Root cause:** `request_data` is stored as `longtext` in MariaDB but Yii2/PHP returns it as an array in some environments (possibly due to PHP driver behavior or column-level caching).  
**Fix:** Defensive type check: `$raw = ...; $data = is_array($raw) ? $raw : json_decode($raw, true);`  
**File:** `frontend/controllers/SiteController.php`

---

### BUG-06: News listing page missing — `/news` had no route
**Symptom:** Clicking "News" in nav → 404 or redirect to homepage.  
**Root cause:** The route `news` → `site/news-list` was never defined. Only `news/<id:\d+>` and `news/<category:...>` existed. No listing page at all.  
**Fix:**  
- Added `actionNewsList()` to `SiteController` fetching all published news.  
- Added route `news` → `site/news-list` to `frontend/config/main.php`.  
- Created `frontend/views/site/news-list.php` with Bootstrap card grid.  
**Files:** `frontend/controllers/SiteController.php`, `frontend/config/main.php`, `frontend/views/site/news-list.php`

---

### BUG-07: News detail — `$id` passed to view instead of News model
**Symptom:** View `frontend/views/site/news.php` receives `$id` from controller but directly calls `$news->newsImagesPrimary->name` — crashes if `newsImagesPrimary` is null.  
**Root cause:** `actionNews($id)` passed only `['id' => $id]` to the view. View did `News::findOne($id)` internally (bad pattern) and had no null-check on `newsImagesPrimary`.  
**Fix:**  
- Updated `actionNews($id)` to fetch News model, add 404 on not-found, pass full object to view.  
- Rewrote `news.php` view to use passed `$news`, with null-safe `$primaryImg` check, proper breadcrumbs, "Back to News" link, and `strip_tags` on HTML content fields.  
**Files:** `frontend/controllers/SiteController.php`, `frontend/views/site/news.php`

---

### BUG-08: Frontend `LoginForm` — `email` required but form uses `username` field
**Symptom:** Frontend login always fails silently. Yii2 JS validation blocks form submission; even when submitted directly, server-side validation error: "email cannot be blank".  
**Root cause:** `frontend/models/LoginForm::rules()` required `['email', 'password']`. But the view renders `$form->field($model, 'username')` → submits `LoginForm[username]`. The `email` property is never populated → validation fails. `getUser()` then calls `findByEmail($this->username)` which is the intended behavior.  
**Fix:** Changed rules to `['username', 'password']`. `getUser()` updated to try email-first then username-fallback so both email and username login work.  
**File:** `frontend/models/LoginForm.php`

---

### BUG-09: Auction detail — lot number ignores stored `lot_number` field
**Symptom:** Auction with `lot_number = 'GM-2026-001'` shows "Lot-N-8" on the detail page.  
**Root cause:** View always called `Auction::generateAuctionLotNumber($auction->id)` regardless of whether a lot number was stored.  
**Fix:** Use stored `$auction->lot_number` if non-empty, fall back to auto-generated otherwise.  
**File:** `frontend/views/product/auction-detail.php`

---

### BUG-10: Short descriptions showing raw HTML tags
**Symptom:** Product/news cards showing `<p>Gold prices moved...` instead of plain text.  
**Root cause:** `short_description` is stored with HTML tags but `Html::encode()` encodes them instead of stripping.  
**Fix:** Applied `strip_tags()` before `Html::encode()` on all `short_description` displays in card views.  
**Files:** `frontend/views/site/news-list.php`, `frontend/views/product/auction-detail.php`

---

### BUG-11: Hardcoded OpenAI API key in committed file
**Symptom:** GitHub push rejected with secret scanning block.  
**Root cause:** `frontend/config/params.php` contained `chatbotApiKey => 'sk-proj-...'` committed to git.  
**Fix:** Redacted key to empty string with comment, added `frontend/config/params.php` to `.gitignore`.  
**File:** `frontend/config/params.php`, `.gitignore`
