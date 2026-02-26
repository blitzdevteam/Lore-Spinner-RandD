# LoreSpinner

AI-powered interactive fiction platform that transforms screenplay scripts into playable, branching narrative experiences with dynamic narration and player-driven choices.

## About

This is the **Staging** version of LoreSpinner, built for demonstration and testing of the Beta release.

## Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Vue 3, Inertia.js v2, Tailwind CSS 4, PrimeVue 4
- **AI:** Laravel AI + OpenAI GPT-5.2
- **Admin:** Filament v4
- **Database:** PostgreSQL 17
- **Infrastructure:** Redis, MinIO (S3), Laravel Sail (Docker)

## Quick Start

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm ci && ./vendor/bin/sail npm run dev
```

## Deployment Requirements

### Server

- PHP 8.4 with extensions: `pgsql`, `redis`, `gd`, `mbstring`, `xml`, `curl`, `zip`
- PostgreSQL 17
- Redis
- Node.js 22+ (build step only)
- Composer 2
- S3-compatible storage (MinIO, AWS S3, etc.)

### Required Environment Variables

| Variable | Purpose |
|----------|---------|
| `OPENAI_API_KEY` | AI narration and story extraction (GPT-5.2) |
| `ELEVENLABS_API_KEY` | Text-to-speech narration |
| `ELEVENLABS_VOICE_ID` | TTS voice selection |
| `DB_*` | PostgreSQL connection |
| `REDIS_*` | Cache and queue backend |
| `AWS_*` | S3-compatible object storage for media |
| `MAIL_*` | SMTP for email verification |

### Deploy Steps

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
```

### Database Seeding (first deploy)

```bash
# Option A — Restore from dump (fast, no API calls)
psql -U $DB_USERNAME -d $DB_DATABASE < database/dump.sql

# Option B — Full AI-powered seed (~10 min, requires OPENAI_API_KEY)
php artisan migrate:fresh --seed
```

### Queue Worker

A persistent queue worker is required for AI processing jobs:

```bash
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
```

## Documentation

Full setup, deployment, and development guides available in [DOCUMENTATION.md](DOCUMENTATION.md).
