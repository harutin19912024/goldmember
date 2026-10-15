# Database updates

There is no Yii console app / migration runner in this project, so schema and
data changes live here as plain SQL files, named `YYYY-MM-DD-<topic>.sql`.

- Every file is **idempotent** (`IF NOT EXISTS`, guarded `INSERT`s) — running it
  twice is harmless.
- Apply in date order.
- **Production**: paste into phpMyAdmin in the hosting panel.
  **Local**: `docker compose exec -T db mariadb -u root -prootpassword gssamru_goldmember < database/updates/<file>.sql`
- After any schema change, clear Yii's schema cache: delete
  `backend/runtime/cache/*` and `frontend/runtime/cache/*`.
