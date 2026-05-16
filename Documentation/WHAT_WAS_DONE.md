# Goldmember — What Was Done

Chronological log of all changes made during Claude sessions.

---

## Session 2026-05-16

### Project Analysis
- Full architectural analysis of the Yii2 Advanced Template app
- Identified it is a **gold/precious metals marketplace** for the Armenian market (`goldmember.am`)
- Mapped all controllers, models, views, and Docker services
- Screenshotted frontend and backend UIs

### Bug Fixes Applied
See `BUGS_AND_FIXES.md` for full details. Summary:
1. Fixed frontend homepage crash (language config in wrong block)
2. Fixed product listing page crash (wrong view content — auto-parts code in gold site)
3. Fixed backend layout null crashes (2 controllers without AccessControl)
4. Fixed "More Details" metals prices page (4 issues: empty sections, wrong subtitle, 390px gap)
5. Fixed `json_decode` type error for `MetalPriceReal`
6. Fixed news system (missing listing page, missing route, bad controller pattern)
7. Fixed frontend `LoginForm` validation mismatch (`email` required, `username` submitted)
8. Fixed auction detail: lot number ignoring stored value
9. Fixed short descriptions showing raw HTML tags
10. Removed hardcoded OpenAI API key from git

### New Features Built
- **News listing page** (`/news`) — Bootstrap card grid with category filters, images, dates, "Read more" links
- **News detail rewrite** — proper null safety, image handling, breadcrumbs, "Back to News" link
- **Metals Prices page rewrite** — moved all data fetching to controller, 4 proper sections:
  - Local Prices (from admin Metal Prices)
  - Global Prices (computed from API snapshot)
  - Global Spot Prices (gold price with change %)
  - Exchange Rates (from admin Exchange Rates, with currency names)
- **Exchange Rates section** — added to more-details page, pulling from admin
- **Live auction created** — auction ID 8 for "Elegant 14K Gold Ring" (LIVE status for testing)
- **Agora system verified** — admin host panel confirmed working, viewer join-stream confirmed working

### Test Suite
- Created `tests/playwright/` directory with 5 test files
- 18 Playwright E2E tests, all passing
- Tests cover: homepage, products, auth pages, backend login, backend CRUD pages

### Infrastructure
- Initialized git repo (`git init`)
- Set up GitHub remote → https://github.com/harutin19912024/goldmember
- Created proper `.gitignore` (excludes secrets, runtime, uploads, vendor)
- Created `CLAUDE.md` with full project context for future sessions
- Created 4 memory files in `~/.claude/projects/.../memory/`
- Created `Documentation/` folder with 5 docs

### Admin Credentials (Docker local)
- Backend admin: `harut` / `admin123`
- Frontend user: email `test@test.com` / `admin123` (or bypass Yii2 JS validation)
- phpMyAdmin: root / `rootpassword`

---

## Known Issues Remaining After This Session
(See `IMPROVEMENTS.md` for full backlog)

1. Cart/order flow not complete — only Service model wired
2. Payment gateway missing
3. Metal price cron job not scheduled
4. Chatbot API key must be set manually in `frontend/config/params.php`
5. Frontend login form — Yii2 client-side JS validation needs `validateOnSubmit: true` in ActiveForm to work in headless browsers; regular browsers are fine
6. Auction bid placement — UI only, no storage
7. Product detail route needs end-to-end verification
