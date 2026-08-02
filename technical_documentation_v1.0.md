# Technical Documentation v1.0

## 1. Project Overview
This project is an E-Commerce platform built with Laravel for the backend and a modern lightweight frontend utilizing HTMX and Alpine.js. It features a custom Admin Panel, Role-Based Access Control (RBAC), Inventory Management, and an API for handling client-side dynamic requests.

## 2. Tech Stack & Libraries Used
- **Backend Framework:** PHP 8.0+
- **Frontend Stack:** HTML5, Vanilla CSS, HTMX (for AJAX and dynamic UI transitions), Alpine.js (for lightweight client-side reactivity).
- **Database:** MySQL / MariaDB (managed via Eloquent ORM)

### Composer Dependencies (Production)
- **`laravel/framework` (v9.19)**: The core Laravel application framework providing routing, MVC, Eloquent ORM, and more.
- **`guzzlehttp/guzzle` (v7.2)**: A PHP HTTP client used for making requests to external APIs and services.
- **`laravel/sanctum` (v3.0)**: A featherweight authentication system for SPAs, mobile applications, and simple, token-based APIs.
- **`laravel/tinker` (v2.7)**: Provides an interactive REPL (Read-Eval-Print Loop) for interacting with the Laravel application from the command line.
- **`opcodesio/log-viewer` (v3.1)**: A beautiful, easy-to-use log viewer dashboard. It allows administrators to read, search, and filter `laravel.log` errors and technical exceptions directly on the live site without SSH access.

### Composer Dependencies (Development)
- **`fakerphp/faker` (v1.9.1)**: Generates fake dummy data for database seeders and testing.
- **`laravel/pint` (v1.0)**: An opinionated PHP code style fixer built on top of PHP-CS-Fixer for clean code formatting.
- **`laravel/sail` (v1.0.1)**: A lightweight command-line interface for interacting with Laravel's default Docker development environment.
- **`mockery/mockery` (v1.4.4)**: A mock object framework used for writing robust unit tests in PHPUnit.
- **`nunomaduro/collision` (v6.1)**: A beautiful error reporting tool tailored for command-line applications and Artisan commands.
- **`phpunit/phpunit` (v9.5.10)**: The standard testing framework for writing unit and feature tests in PHP.
- **`spatie/laravel-ignition` (v1.0)**: Provides a beautiful, detailed error page when exceptions occur during local development.

## 3. Core Architecture
The architecture is monolithic with a strong separation of concerns:
- **Controllers:** Handle HTTP requests and return views or JSON responses.
  - `AdminController`: Manages admin dashboard, users, inventory, and reports.
  - `ProductController`: Manages product catalog and storefront logic.
  - `StorefrontController`: Manages public-facing pages and cart interactions.
- **Models:** Eloquent models map to database tables (`User`, `Product`, `Order`, `Category`, `Inventory`, `Role`, etc.).
- **Views:** Blade templates located in `resources/views/`. Uses partials for HTMX responses.
- **Routing:** 
  - `routes/web.php` handles standard page requests and HTMX partial requests.
  - `routes/api.php` handles standard API endpoints (if any) and structured data interactions.

## 4. Setup Instructions
1. Clone the repository.
2. Run `composer install` (ensure PHP 8.0+ is installed).
3. Copy `.env.example` to `.env` and configure your database credentials.
4. Run `php artisan key:generate`.
5. Run migrations and seeders: `php artisan migrate --seed`.
6. (Optional) Run `composer update` to pull in the newly added `opcodesio/log-viewer`.
7. Serve the application using `php artisan serve`.

## 5. Live Error Viewing (Log Viewer)
We have integrated **Opcodes Log Viewer**. 
To access live site errors:
- Visit `/log-viewer` (accessible based on authentication rules defined in the Log Viewer configuration).
- This will allow you to see the latest technical errors, stack traces, and system logs without SSH access to the server.

## 6. Directory Structure Highlights
- `app/Http/Controllers/`: Contains all business logic controllers.
- `resources/views/`: Contains all Blade templates. Admin UI is separated from Storefront UI.
- `public/css/`: Vanilla CSS files (`admin.css`, etc.). No CSS preprocessors are actively strictly required.
- `routes/web.php`: Primary routing file.

## 7. Version History
- **v1.0**: Initial compilation of technical documentation. Added integrated logger and try-catch safety across the application.
