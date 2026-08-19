# CityStyleWears

An e-commerce storefront and admin panel built with Laravel, Livewire, and Tailwind CSS, with Flutterwave payment integration.

## Documentation

The full admin panel guide (logging in, managing products, categories, orders, customers, coupons, homepage slides, FAQs, and site settings) lives in [docs/ADMIN_GUIDE.md](docs/ADMIN_GUIDE.md).

## Tech Stack

- [Laravel](https://laravel.com/docs) — application framework
- [Livewire](https://livewire.laravel.com) — reactive admin UI components
- [Tailwind CSS](https://tailwindcss.com) + [Vite](https://vitejs.dev) — front-end tooling
- [Flutterwave](https://developer.flutterwave.com) — payment processing

## Getting Started

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## License

This project is proprietary and not licensed for public use or redistribution.
