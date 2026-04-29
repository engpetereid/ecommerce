# EcommerceSys

A full-featured e-commerce web application built with **Laravel 12**, **Livewire 3**, and **Filament 3**. It provides a reactive storefront for customers and a powerful admin panel for store managers — all within a single Laravel application.

---

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Database Design](#database-design)
- [Admin Panel](#admin-panel)
- [Architecture Notes](#architecture-notes)
- [Future Improvements](#future-improvements)
- [License](#license)

---

## Description

EcommerceSys solves the problem of building a reactive, real-time shopping experience without writing a separate JavaScript SPA. By leveraging **Laravel Livewire**, the entire storefront — from browsing products to completing checkout — updates dynamically without full-page reloads. The admin panel, powered by **Filament**, gives managers a fully-featured back-office to manage products (including variants and translations), categories, orders, and customer reviews.

---

## Features

### Storefront (Customer-Facing)
- **Product Listing** — Homepage displays up to 12 active products, newest first.
- **Product Detail Page** — Image gallery, rich description, variant selection (e.g., Size, Color), live stock validation, and quantity controls.
- **Product Variants** — Dynamic price and stock updates when a customer selects a different variant (color, size, etc.).
- **Shopping Cart** — Cookie-persisted cart with add, remove, increment, and decrement actions. Cart count updates in real time in the navbar.
- **Checkout** — Validated order form capturing name, phone, address, and payment method (Cash on Delivery). Redirects to a success page on completion.
- **Reactive Navbar** — Cart item count badge synced across all pages via Livewire events.

### Admin Panel (`/admin`)
- **Product Management** — Create, edit, and delete products with a tabbed form (General Info, Images, Variants). Auto-generates slugs from product names.
- **Category Management** — Hierarchical categories with parent/child support. Auto-generated slugs.
- **Order Management** — View and update order status (Pending → Processing → Shipped → Delivered → Cancelled). Live badge on nav shows pending order count.
- **Review Management** — Moderate customer product reviews with approval workflow.
- **Multilingual Content** — Product and category names/descriptions are translatable via `spatie/laravel-translatable`.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 |
| PHP Version | PHP 8.2+ |
| Reactive UI | Livewire 3 |
| Admin Panel | Filament 3 |
| Frontend Build | Vite 7 |
| CSS Framework | Tailwind CSS 4 |
| Database (default) | SQLite |
| Database (production) | MySQL / PostgreSQL (configurable) |
| Translations | `spatie/laravel-translatable` ^6.12 |
| Translation UI | `filament/spatie-laravel-translatable-plugin` ^3.3 |
| Queue / Cache | Database driver (default) |
| Testing | PHPUnit 11 |

---

## Installation

### Prerequisites

- PHP >= 8.2 with extensions: `pdo_sqlite` (or `pdo_mysql` for MySQL), `mbstring`, `openssl`, `xml`, `curl`
- Composer >= 2.x
- Node.js >= 18.x & npm

### Quick Setup (automated)

The project ships with a `composer setup` script that handles everything:

```bash
git clone <repository-url> ecommercesys
cd ecommercesys
composer setup
```

This single command runs: `composer install` → copies `.env` → generates app key → runs migrations → `npm install` → `npm run build`.

### Manual Step-by-Step Setup

```bash
# 1. Clone the repository
git clone <repository-url> ecommercesys
cd ecommercesys

# 2. Install PHP dependencies
composer install

# 3. Set up environment file
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env
# Default uses SQLite — no changes needed for local dev.
# For MySQL, uncomment and fill in DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD.

# 5. Create the SQLite database file (if using SQLite)
touch database/database.sqlite

# 6. Run migrations
php artisan migrate

# 7. Install Node dependencies and compile assets
npm install
npm run build

# 8. Create the admin user
php artisan make:filament-user
```

### Storage Link (for product images)

```bash
php artisan storage:link
```

---

## Usage

### Running the Development Server

The project includes a `composer dev` script that starts all required processes concurrently:

```bash
composer dev
```

This starts:
- `php artisan serve` — Laravel development server at `http://127.0.0.1:8000`
- `php artisan queue:listen` — Processes queued jobs
- `php artisan pail` — Real-time log viewer
- `npm run dev` — Vite HMR for frontend assets

### Running Tests

```bash
composer test
# or
php artisan test
```

### Application URLs

| URL | Description |
|---|---|
| `http://127.0.0.1:8000/` | Homepage — product listing |
| `http://127.0.0.1:8000/products/{slug}` | Product detail page |
| `http://127.0.0.1:8000/cart` | Shopping cart |
| `http://127.0.0.1:8000/checkout` | Checkout form |
| `http://127.0.0.1:8000/success` | Order success page |
| `http://127.0.0.1:8000/admin` | Filament admin panel |

---

## Project Structure

```
ecommercesys/
├── app/
│   ├── Filament/
│   │   └── Resources/              # Admin panel resource definitions
│   │       ├── CategoryResource.php
│   │       ├── OrderResource.php
│   │       ├── ProductResource.php
│   │       └── ReviewResource.php
│   ├── Helpers/
│   │   └── CartManagement.php      # Stateless cart logic (cookie-based)
│   ├── Livewire/                   # Livewire full-page components (storefront)
│   │   ├── CartPage.php
│   │   ├── CheckoutPage.php
│   │   ├── HomePage.php
│   │   ├── Navbar.php
│   │   ├── ProductDetail.php
│   │   └── SuccessPage.php
│   └── Models/                     # Eloquent models
│       ├── Category.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Product.php
│       ├── ProductVariant.php
│       ├── Review.php
│       └── User.php
├── database/
│   ├── migrations/                 # All database schema definitions
│   ├── factories/                  # Model factories for testing/seeding
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── css/app.css                 # Tailwind CSS entry point
│   ├── js/app.js                   # JS entry point (Axios, Echo)
│   └── views/
│       ├── components/layouts/     # Blade layout templates
│       └── livewire/               # Blade views for each Livewire component
│           ├── cart-page.blade.php
│           ├── checkout-page.blade.php
│           ├── home-page.blade.php
│           ├── navbar.blade.php
│           ├── product-detail.blade.php
│           └── success-page.blade.php
├── routes/
│   └── web.php                     # All storefront routes
├── .env.example                    # Environment variable template
├── composer.json                   # PHP dependencies & scripts
├── package.json                    # Node dependencies
├── tailwind.config.js
└── vite.config.js
```

---

## Database Design

### Entity-Relationship Overview

```
users
 └──< orders (user_id)
        └──< order_items (order_id)
               ├── product_id → products
               └── product_variant_id → product_variants

categories (self-referencing via parent_id)
 └──< products (category_id)
        ├──< product_variants (product_id)
        └──< reviews (product_id)
               └── user_id → users
```

### Tables

#### `users`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `name` | string | |
| `email` | string | unique |
| `password` | string | bcrypt hashed |
| `email_verified_at` | timestamp | nullable |

#### `categories`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `name` | string | translatable |
| `slug` | string | unique |
| `parent_id` | bigint | FK → categories (self-reference, nullable) |
| `is_visible` | boolean | default: true |
| `image` | string | nullable |
| `description` | longText | translatable, nullable |

#### `products`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `category_id` | bigint | FK → categories, nullable |
| `name` | string | translatable |
| `slug` | string | unique |
| `description` | longText | translatable, nullable |
| `image` | string | main image path, nullable |
| `images` | json | gallery image paths, nullable |
| `price` | decimal(10,2) | base price |
| `is_active` | boolean | default: true |
| `is_featured` | boolean | default: false |
| `has_variants` | boolean | default: false |

#### `product_variants`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `product_id` | bigint | FK → products, cascade delete |
| `sku` | string | unique, nullable |
| `price` | decimal(10,2) | overrides product price if set |
| `stock` | integer | default: 0 |
| `options` | json | e.g. `{"Color": "Red", "Size": "L"}` |

#### `orders`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `user_id` | bigint | FK → users, nullable (guest) |
| `number` | string | unique, e.g. `ORD-XXXXXXXXXX` |
| `total_price` | decimal(10,2) | |
| `shipping_price` | decimal(10,2) | default: 50 |
| `status` | enum | `pending`, `processing`, `shipped`, `delivered`, `cancelled` |
| `payment_method` | enum | `cod` (Cash on Delivery) |
| `notes` | text | nullable |
| `address_info` | json | `{first_name, last_name, phone, street_address, city, zip_code}` |

#### `order_items`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `order_id` | bigint | FK → orders, cascade delete |
| `product_id` | bigint | FK → products, nullable |
| `product_variant_id` | bigint | FK → product_variants, nullable |
| `quantity` | integer | default: 1 |
| `unit_price` | decimal(10,2) | price at time of purchase |
| `total_price` | decimal(10,2) | `quantity × unit_price` |

#### `reviews`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `user_id` | bigint | FK → users, cascade delete |
| `product_id` | bigint | FK → products, cascade delete |
| `comment` | text | nullable |
| `rating` | integer | 1–5 |
| `is_approved` | boolean | default: false (requires admin approval) |

---

## Admin Panel

The admin panel is provided by Filament 3 and is accessible at `/admin`. Create your first admin user with:

```bash
php artisan make:filament-user
```

### Admin Resources

| Resource | Features |
|---|---|
| **Products** | Tabbed form (General Info, Images, Variants), translatable name/description, auto-slug, category assignment, featured/active toggles, image gallery upload, dynamic variant repeater (SKU, price, stock, key-value options) |
| **Categories** | Hierarchical parent-child structure, translatable, auto-slug, visibility toggle |
| **Orders** | Status lifecycle management, read-only order items, customer address view, pricing summary, live pending-order badge on sidebar nav |
| **Reviews** | List and manage customer reviews with approval moderation |

### Order Status Lifecycle

```
pending → processing → shipped → delivered
                                  ↓
                               cancelled (from any stage)
```

---

## Architecture Notes

### Cookie-Based Cart (`CartManagement`)

The cart is intentionally **stateless** — no database writes occur until checkout. All cart data is serialized as JSON in a browser cookie (`cart_items`) with a 30-day TTL. This approach avoids orphaned cart records and simplifies horizontal scaling.

Cart items are keyed by `"{product_id}-{variant_id}"`, allowing the same product with different variants to coexist as separate line items.

### Livewire Event Bus for Navbar Sync

The cart item count in the navbar is kept in sync across full-page Livewire components using the `cart_updated` event:

- **Emitters:** `HomePage`, `ProductDetail`, `CartPage`, `CheckoutPage`
- **Listener:** `Navbar` (via `#[On('cart_updated')]`)

This avoids any global state management library while keeping the UI reactive.

### Full-Page Livewire Components as Routes

Every storefront page is a Livewire full-page component registered directly in `routes/web.php`. There are no traditional controllers for the storefront, keeping the request lifecycle clean and components self-contained.

### Product Variant Selection

When a customer changes a variant option (e.g., selects a different color), `updatedSelectedOptions()` fires automatically via Livewire's property update hooks. It matches the selected `options` array against each variant using `array_diff_assoc`, then updates the displayed price and stock count reactively — no API calls required.

### Multilingual Support

`spatie/laravel-translatable` stores translations as JSON directly in the database column (e.g., `{"en": "T-Shirt", "ar": "تي شيرت"}`). The Filament translatable plugin adds a locale switcher to the admin form automatically for fields declared in `$translatable`.

---

## Future Improvements

- **User Authentication for Storefront** — Implement login/registration so users can view their order history.
- **Payment Gateway Integration** — Add Stripe or PayPal support beyond the current Cash on Delivery option.
- **Product Search & Filtering** — Add full-text search and category/price range filters to the homepage.
- **Coupon & Discount System** — Apply percentage or fixed discount codes at checkout.
- **Email Notifications** — Send order confirmation and status-update emails to customers.
- **Shipping Price Calculator** — Dynamic shipping rates based on address or weight instead of a flat fee.
- **Inventory Management** — Deduct stock from `product_variants` when an order is placed and restore it on cancellation.
- **Featured Products Section** — Leverage the `is_featured` flag to create a dedicated homepage section.
- **Guest Checkout** — Orders already support a nullable `user_id`; just expose a guest flow in the UI.
- **Review Submission UI** — Add a form on the product page for authenticated users to submit reviews.

---

## Contributing

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -m 'feat: add your feature'`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a Pull Request.

Please run `composer test` and `./vendor/bin/pint` (code style fixer) before submitting.

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
