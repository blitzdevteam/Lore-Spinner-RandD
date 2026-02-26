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

## Documentation

Full setup, deployment, and development guides available in [DOCUMENTATION.md](DOCUMENTATION.md).
