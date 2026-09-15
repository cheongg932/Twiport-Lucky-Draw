# Lucky Draw

Cinematic lucky-draw experience, built UI-first on **PHP 8.2**, **Laravel 12**, **Vue 3**, **TypeScript**, **Vite**, and **Tailwind CSS**, with **GSAP**, **Motion**, **Lenis**, and **Anime.js**.

## Pages

- `/` — animated landing, prize wall, and game picker
- `/spin` — gold spin wheel
- `/scratch` — holographic scratch card
- `/slots` — neon slot machine

Rewards are product-shaped: iPhone 16 Pro, MacBook Air, iPad Pro, Watch Ultra, AirPods Pro, and gift vouchers.

## Local setup

Requires PHP 8.2+, Composer, and Node 20+.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run dev
```

In another terminal:

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

Production build:

```bash
npm run build
```

## API

- `GET /api/prizes` — catalog and wheel order
- `POST /api/draw/{spin|scratch|slots}` — weighted draw used by the three games
