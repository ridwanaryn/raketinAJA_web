# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**raketinAJA** — a sports field (padel, tennis, badminton) booking platform built with Laravel 13 + Blade + Tailwind CSS 4.

## Commands

### Full Setup (first time)
```bash
composer setup   # install deps, generate key, migrate, npm install, npm build
```

### Development
```bash
composer dev     # runs all dev processes concurrently:
                 #   php artisan serve  (app server)
                 #   php artisan queue:listen
                 #   php artisan pail   (log viewer)
                 #   npm run dev        (Vite HMR)
```

### Testing
```bash
composer test              # run full test suite (php artisan test)
php artisan test --filter BookingConflictTest   # run a single test class
```

### Frontend
```bash
npm run dev     # Vite dev server with Blade hot-reload
npm run build   # production asset build
```

### Database
```bash
php artisan migrate
php artisan db:seed    # seeds ~250 records: 7 fields, realistic bookings
```

## Architecture

### Stack
- **Laravel 13** (PHP 8.3+) connected to **Supabase PostgreSQL** via the IPv4 Session Pooler (`aws-1-<region>.pooler.supabase.com:5432`, user `postgres.<project_ref>`, sslmode `require`)
- **Blade** templates + **Tailwind CSS 4** via Vite
- Sessions & cache use the `file` driver (avoid extra round-trips to Supabase during local dev); queue is `sync`
- Required PHP extensions: `pdo_pgsql`, `pgsql` (must be enabled in `php.ini` — they ship as DLLs but are commented out by default on Windows)

### Supabase integration
- Project: `booking sport app` (ref `zagjpyatptjxvehtermo`, region `ap-northeast-2`)
- Schema is managed by Laravel migrations; the Supabase MCP `apply_migration` was used for initial bootstrap but ongoing schema changes should go through `php artisan make:migration` so the `migrations` table stays in sync
- Direct hostname `db.<ref>.supabase.co` is IPv6-only on the free tier — always use the Session Pooler hostname instead
- RLS is currently **disabled** on all tables since Laravel talks to Postgres as the `postgres` role (not via PostgREST). Do not enable RLS unless adding a Supabase-client path

### User Roles & Auth
Two roles on the `User` model: `player` (can book and review) and `owner` (can manage fields). Role checks are done inline in controllers via `Auth::user()->isOwner()` / `isPlayer()`. There is no dedicated role middleware — authorization is enforced at the controller level. The `User` model does **not** implement `MustVerifyEmail`; `AuthController::register` calls `Auth::login($user)` immediately after creation, so accounts are usable without any verification step. The `email_verified_at` column exists on the `users` table but is intentionally never populated.

### Request Flow
Routes (`routes/web.php`) → Controllers (`app/Http/Controllers/`) → Eloquent Models (`app/Models/`) → Blade views (`resources/views/`).

Key controllers:
- `AuthController` — login, register, logout, password reset
- `FieldController` — public field browsing and detail page with slot availability
- `BookingController` — confirm flow, create booking (with double-booking prevention), list bookings
- `OwnerDashboardController` — owner stats dashboard, field CRUD
- `ReviewController` — post-booking review creation

### Booking Conflict Logic
Double-booking is prevented by an overlap check in `BookingController`: `(StartA < EndB) AND (EndA > StartB)`. All 7 overlap scenarios are covered in `tests/Feature/BookingConflictTest.php`.

### Time Slot System
Fields use fixed 1.5-hour slots defined in `FieldController` (6 slots/day: 09:00–19:00 with a lunch gap). Slots are calculated server-side and passed to the Blade view as available/booked.

### Key Model Relationships
```
User (owner) → hasMany → Field
User (player) → hasMany → Booking
Field         → hasMany → Booking
Booking       → hasOne  → Review
```
`Field::features` is cast as a JSON array. `Field` has two query scopes: `active()` and `bySport($sport)`.

### Owner Dashboard Analytics
Computed in `OwnerDashboardController`: revenue MTD, active bookings count, peak utilization (most-booked start_time), and daily capacity percentage (booked / fields × 6 slots).

### Frontend Conventions
- Tailwind CSS 4 with Material Design 3 color tokens as CSS variables in `resources/css/app.css`
- Glassmorphism navbar (backdrop-blur), skew/kinetic hover effects
- Vite watches Blade files for hot reload (`vite.config.js`)
