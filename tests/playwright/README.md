# Goldmember — Playwright E2E Tests

## Quick Start

```bash
pip install pytest-playwright
playwright install chromium

# Run all tests
cd /var/www/html/goldmember
pytest tests/playwright/ -v

# Run a specific suite
pytest tests/playwright/tests/test_frontend_homepage.py -v

# Run with headed browser (watch mode)
pytest tests/playwright/ --headed -v
```

## Test Suites

| File | What it covers |
|------|----------------|
| `test_frontend_homepage.py` | Homepage loads, hero text, navigation, stats, language switch |
| `test_frontend_products.py` | Product listing, auction page, best-offer page |
| `test_frontend_auth.py` | Frontend login, register, forgot-password pages |
| `test_backend_auth.py` | Backend login form, wrong credentials, admin success, unauth redirect |
| `test_backend_crud.py` | Backend CRUD pages: products, news, categories, sliders, users |

## Prerequisites

- Docker Compose running: `docker compose up -d` from project root
- Admin user `harut` with password `admin123` in DB (set via `docker compose exec db mariadb ...`)

## Adding Tests

Put new test files in `tests/playwright/tests/test_<area>.py`.
Use `helpers/auth.py` → `backend_login(page)` for authenticated backend tests.
