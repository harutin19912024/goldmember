# Goldmember — Project Documentation Overview

> **This folder documents what has been built, what works, what is broken, and what to build next.**
> Always cross-reference with `CLAUDE.md` in the project root for session-startup instructions.

---

## Document Index

| File | Contents |
|------|----------|
| [OVERVIEW.md](OVERVIEW.md) | This file — index and quick-status summary |
| [WHAT_WAS_DONE.md](WHAT_WAS_DONE.md) | Full log of changes made in the Claude sessions |
| [BUGS_AND_FIXES.md](BUGS_AND_FIXES.md) | All bugs found, their root causes, and fixes applied |
| [IMPROVEMENTS.md](IMPROVEMENTS.md) | Prioritized backlog of features to build and improvements to make |
| [ARCHITECTURE.md](ARCHITECTURE.md) | System architecture, data flow, and component relationships |

---

## Quick Status (as of 2026-05-16)

### What is working ✅
- Frontend homepage (language config fixed)
- Product listing page (`/search`) — gold product grid
- News listing (`/news`) and detail pages
- More-details / Metals Prices page — Local Prices, Global Prices, Global Spot, Exchange Rates
- Backend admin panel — Metal Prices, Exchange Rates, Products, News, Slider, Users
- Auction system — listing + detail pages (upcoming/live/ended states)
- Agora live video — admin host panel + viewer "Join Stream" interface
- Docker setup (frontend :8080, backend :8090, phpMyAdmin :8081)
- Git repo → https://github.com/harutin19912024/goldmember

### What is broken / incomplete ❌
- Frontend login — Yii2 client-side validation blocks Playwright (works in real browsers)
- Cart / order flow — `CartController` mixes Service and Product models; order placement untested
- Payment gateway — not integrated
- Metal price auto-fetch — cron/console command exists but not scheduled
- Email notifications — SwiftMailer configured for file transport only
- Chatbot API key — stored in gitignored `frontend/config/params.php`, needs env var setup

### Partially working ⚠️
- Product detail pages — need to verify `/product/product?id=X` route
- Auction bid submission — UI only, no bid-storage backend
- User profile section — models exist but profile edit flow not tested end-to-end
