# Goldmember - Docker Local Development

## Services and ports

| Service    | URL / port               | Notes                        |
|------------|--------------------------|------------------------------|
| Frontend   | http://localhost:8080    | `frontend/web/`              |
| Backend    | http://localhost:8090    | `backend/web/`               |
| phpMyAdmin | http://localhost:8081    | root / `rootpassword`        |
| MariaDB    | localhost:3306           | host-side access for GUI     |

## First-time setup

```bash
chmod +x docker/mysql/01-init.sh
docker compose up --build
```

The SQL dump is imported automatically on the first startup only.
`docker/mysql/01-init.sh` imports `gssamru_goldmember.sql` into `gssamru_goldmember`.

## Day-to-day usage

```bash
docker compose up -d
docker compose down
docker compose logs -f app
docker compose logs -f db
docker compose exec app bash
docker compose exec app php yii <command>
```

## Re-import the database

```bash
docker compose down -v
docker compose up --build
```

## File layout

```text
docker/
  apache/
    frontend.conf   # Apache vhost: 8080 -> frontend/web
    backend.conf    # Apache vhost: 8090 -> backend/web
  config/
    db-local.php    # Docker DB config mounted over common config
  mysql/
    01-init.sh      # Imports gssamru_goldmember.sql on first start
  php/
    php.ini         # Custom PHP settings
Dockerfile
docker-compose.yml
```

## Database credentials (Docker)

| Setting  | Value |
|----------|-------|
| Host     | `db` (inside Docker) / `localhost:3306` (host) |
| Database | `gssamru_goldmember` |
| User     | `gssamru_autoimport` |
| Password | `bGtVSaf2rv6hLWN` |
| Root pw  | `rootpassword` |
