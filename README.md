# Asqi Apparel

Asqi Apparel is a brutalist-themed e-commerce web application built with **Laravel 11**, **Tailwind CSS 4**, and **Alpine.js**.

## 🚀 Features
- **Brutalist Design System**: Bold typography, high-contrast borders, and stark layouts.
- **Product Management**: Full CRUD for products, categories, and variants with an admin dashboard.
- **Dynamic Product Variants**: Drag-and-drop variant sorting, automatic SKU generation, and color/size tracking.
- **Shopping Cart & Wishlist**: Real-time cart updates and wishlist functionality.
- **Live Search**: Instant AJAX-based product search functionality.

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed on your local machine:
- **PHP** >= 8.2
- **Composer** (PHP dependency manager)
- **Node.js & NPM** (for compiling frontend assets)
- **MySQL / MariaDB** (or any database supported by Laravel)

---

## ⚙️ Installation Guide

Follow these steps to set up the project locally:

### 1. Clone the Repository
If you haven't already, clone the repository to your local machine:
```bash
git clone https://github.com/YOUR_USERNAME/asqi-apparel.git
cd asqi-apparel
```

### 2. Install PHP Dependencies
Install all required Laravel packages using Composer:
```bash
composer install
```

### 3. Setup Environment Variables
Duplicate the example environment file and rename it to `.env`:
```bash
cp .env.example .env
```
Open the `.env` file and configure your database credentials:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asqi_apparel
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
Run the following command to generate the Laravel application encryption key:
```bash
php artisan key:generate
```

### 5. Run Migrations & Database Seeder
Create the necessary database tables and populate them with default data (categories, products, and variants):
```bash
php artisan migrate:fresh --seed
```

### 6. Install & Compile Frontend Assets (NPM)
Install all frontend dependencies (Tailwind CSS, Alpine.js, etc.) and compile the assets:
```bash
# Install dependencies
npm install

# Build assets for production
npm run build
```
*(Note: If you are actively developing and changing CSS/JS files, you can leave `npm run dev` running in a separate terminal tab).*

### 7. Link Storage (Optional but Recommended)
To ensure product images and uploads are publicly accessible:
```bash
php artisan storage:link
```

### 8. Run the Local Server
Start Laravel's built-in development server:
```bash
php artisan serve
```

The application should now be accessible at [http://localhost:8000](http://localhost:8000).

---

## 🎨 Technology Stack
- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS v4 + PostCSS
- **Database**: MySQL

## 👤 Admin Access
You can access the admin panel by logging into any user account that has an `is_admin` boolean set to true in the database. (If using standard Auth, ensure your user account is promoted to admin).
