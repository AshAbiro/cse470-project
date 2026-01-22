# Amusement Park Management System

Laravel-based web application for managing an amusement park with admin, staff, and client portals.

## Start here
- Read `DOCS.md` for setup, configuration, development, API, deployment, and roadmap details.
- The Quick Start and Setup sections are inside `DOCS.md`.
- Previous standalone guides are archived in `docs/archive/`.

## Local development (short)
1. Install PHP dependencies: `composer install`
2. Create environment file: `copy .env.example .env`
3. Generate app key: `php artisan key:generate`
4. Configure database in `.env`
5. Run migrations: `php artisan migrate`
6. Start server: `php -S localhost:8000 -t public/`

## Tests
- Run all tests: `php artisan test`
