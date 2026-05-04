# Appointment System — API & Web

A Laravel-based dental / healthcare appointment app. After signing up and logging in, users pick a service and a doctor, then book from available time slots. They can list their appointments and cancel upcoming ones. The project includes a small **REST API** (public service list and Sanctum-protected user info) alongside a **Blade** UI.

## Features

- **Authentication:** Register, login, logout (session)
- **Booking:** Service and doctor selection, fixed time slots (09:00–16:00), date format `d.m.Y`
- **Conflict prevention:** No overlapping bookings for the same doctor; MySQL `GET_LOCK` serializes concurrent requests
- **Service duration:** End time is derived from the selected service duration
- **My appointments:** Lists the user’s bookings with service and doctor details; future appointments can be cancelled
- **API:** `GET /api/services` (public), `GET /api/user` (requires Laravel Sanctum authentication)

## Tech stack

- PHP **8.2+**
- **Laravel 12**
- **Laravel Sanctum** (API token / SPA auth)
- Database: intended for **MySQL** (e.g. `GET_LOCK`)

## Setup

```bash
git clone https://github.com/melihcelikel1/randevu-sistemi-api.git
cd randevu-sistemi-api
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then:

```bash
php artisan migrate
php artisan db:seed   # sample services & doctors (ServiceSeeder, DoctorSeeder)
php artisan serve
```

Frontend assets for local development:

```bash
npm install
npm run dev
```

You can also run `composer run setup` for a one-shot install (verify `.env` and DB settings for your environment).

## Web routes (summary)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/` | Home |
| GET/POST | `/giris`, `/kayit-ol` | Login & register (guest) |
| POST | `/cikis` | Logout (authenticated) |
| GET | `/randevu-al` | Booking form |
| GET | `/randevu-musaitlik` | Busy slots for doctor + date (JSON) |
| POST | `/randevu-al` | Create appointment |
| GET | `/randevularim` | User’s appointments |
| POST | `/randevularim/{appointment}/iptal` | Cancel appointment |

## API routes (summary)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/services` | Service list |
| GET | `/api/user` | Authenticated user (`auth:sanctum`) |

The API is served under `APP_URL/api` by default. See the [Laravel Sanctum docs](https://laravel.com/docs/sanctum) for token usage.

## Project layout (summary)

- `app/Http/Controllers/` — `AuthController`, `AppointmentController`, `ServiceController`
- `app/Models/` — `User`, `Appointment`, `Service`, `Doctor`
- `database/migrations/` — users, services, doctors, appointments, Sanctum tokens
- `resources/views/` — Blade templates (layouts, auth, booking pages)
- `routes/web.php` — web UI
- `routes/api.php` — API endpoints

## Security

Never commit `.env` to the repository. In production, set `APP_DEBUG=false` and restrict database access with strong credentials.

## License

This project may be distributed under the **MIT** License, consistent with the Laravel ecosystem; the Laravel framework is [MIT licensed](https://opensource.org/licenses/MIT).
