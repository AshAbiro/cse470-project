# Amusement Park Management System

Laravel web app with admin, staff, and client portals for an amusement park.

## Quick start
1. Install PHP deps: `composer install`
2. Create env file: `copy .env.example .env`
3. Generate app key: `php artisan key:generate`
4. Run migrations: `php artisan migrate`
5. (Optional) Seed demo data: `php artisan db:seed`
6. Start server: `php -S localhost:8000 -t public/`

Open: `http://localhost:8000`

## Useful commands
- Run tests: `php artisan test`
- Build assets: `npm install` then `npm run build`
- Dev assets (hot reload): `npm run dev`

## API (v1)
- Base path: `/api/v1`
- Auth: Sanctum (`/sanctum/csrf-cookie` + login)
- API spec: `public/openapi.yaml` (see admin page: `/admin/api-docs`)

## Future upgrades
- See `FUTURE_UPGRADES.md` for roadmap ideas by category.

## Notes
- Default DB is SQLite (see `.env`).
- Seeded demo accounts (if you run the seeder) use password: `password`.
