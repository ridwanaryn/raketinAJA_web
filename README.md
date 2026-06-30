# raketinAJA

Platform pemesanan lapangan olahraga untuk padel, tenis, dan badminton yang dibangun dengan Laravel 13, Blade, dan Tailwind CSS 4.

## Tentang Proyek

raketinAJA membantu pemain menemukan lapangan yang tersedia dan membantu pemilik lapangan mengelola jadwal, pemesanan, dan statistik bisnis secara sederhana.

### Fitur Utama

- Pencarian dan daftar lapangan berdasarkan olahraga
- Detail lapangan dengan slot waktu yang tersedia
- Proses pemesanan yang mencegah bentrokan jadwal
- Dashboard untuk pemilik lapangan
- Sistem ulasan setelah pemesanan selesai
- Role-based access untuk player dan owner

## Teknologi yang Digunakan

- Laravel 13
- PHP 8.3+
- PostgreSQL via Supabase
- Blade + Tailwind CSS 4
- Vite untuk frontend assets
- PHPUnit untuk testing

## Prasyarat

Sebelum menjalankan proyek, pastikan perangkat sudah memiliki:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- Ekstensi PHP: `pdo_pgsql`, `pgsql`

## Quick Start

### 1. Clone repository

```bash
git clone <repository-url>
cd raketinAJA
```

### 2. Siapkan environment

```bash
cp .env.example .env
```

Set konfigurasi database sesuai Supabase. Contoh:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-northeast-2.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.zagjpyatptjxvehtermo
DB_PASSWORD=your-supabase-password
```

> Gunakan hostname Session Pooler Supabase karena host langsung dari Supabase bersifat IPv6-only pada tier gratis.

### 3. Install dependency dan setup awal

```bash
composer setup
```

Perintah ini akan:

- menginstall dependency PHP
- menyalin file .env jika belum ada
- menghasilkan application key
- menjalankan migrasi database
- menginstall dependency frontend
- membangun assets produksi

### 4. Jalankan aplikasi

Untuk mode development:

```bash
composer dev
```

Aplikasi akan menjalankan beberapa proses secara paralel:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail`
- `npm run dev`

Setelah itu, buka `http://localhost:8000`.

## Perintah Penting

### Development

```bash
composer dev
npm run dev
npm run build
```

### Database

```bash
php artisan migrate
php artisan db:seed
```

### Testing

```bash
composer test
php artisan test --filter BookingConflictTest
```

## Struktur Proyek

```text
app/
  Http/Controllers/
  Models/
config/
database/
  migrations/
  seeders/
public/
resources/
  views/
routes/
tests/
```

## Alur Aplikasi

- Route → Controller → Model → Blade View
- Booking conflict dicegah dengan pengecekan overlap:
  `(StartA < EndB) AND (EndA > StartB)`
- Slot waktu lapangan bersifat tetap (6 slot per hari, 09.00–19.00 dengan jeda makan)

## Peran Pengguna

- `player`: dapat mencari lapangan, melakukan pemesanan, dan memberi review
- `owner`: dapat mengelola lapangan, melihat dashboard, dan mengawasi transaksi

## Seed Data

Seeder menyediakan data realistis untuk pengujian dan demo, termasuk beberapa lapangan dan booking sample.

## Lisensi

Proyek ini dilisensikan di bawah MIT License.
