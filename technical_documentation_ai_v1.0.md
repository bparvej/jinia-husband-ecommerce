# Technical Documentation for AI Agents (v1.0)

## System Prompt Context
You are an AI coding assistant. This project is a Laravel 9 monolith. When modifying this project, adhere strictly to the conventions and architectural boundaries documented below.

## Tech Stack Rules
- **Backend:** Laravel 9, PHP 8.0+. Use modern PHP features.
- **Frontend:** HTML5, Vanilla CSS, HTMX, Alpine.js. DO NOT use Tailwind CSS. DO NOT add heavy frontend frameworks like React or Vue. 
- **Routing:** Prefer returning Blade views. For dynamic interactions, return HTMX partials.
- **Logging:** Use `Illuminate\Support\Facades\Log` within `try-catch` blocks for all critical paths. Live errors are tracked via `opcodesio/log-viewer`.

### Composer Dependencies (Production)
- **`laravel/framework` (v9.19)**: Core framework.
- **`guzzlehttp/guzzle` (v7.2)**: HTTP client.
- **`laravel/sanctum` (v3.0)**: Authentication system.
- **`laravel/tinker` (v2.7)**: Artisan REPL.
- **`opcodesio/log-viewer` (v3.1)**: Dashboard for viewing `laravel.log`.

### Composer Dependencies (Development)
- **`fakerphp/faker` (v1.9.1)**: Dummy data generator.
- **`laravel/pint` (v1.0)**: Code style fixer.
- **`laravel/sail` (v1.0.1)**: Docker dev environment.
- **`mockery/mockery` (v1.4.4)**: Mocking framework.
- **`nunomaduro/collision` (v6.1)**: CLI error reporting.
- **`phpunit/phpunit` (v9.5.10)**: Testing framework.
- **`spatie/laravel-ignition` (v1.0)**: Local error page.

## Architecture & Code Conventions
1. **HTMX Integration:** 
   - Requests with `hx-request` header should generally return HTML partials (located in `resources/views/.../partials/`), NOT full page reloads.
   - Example: `if ($request->headers->has('hx-request')) { return view('partials.item', $data); }`
2. **Alpine.js:** Used for localized state (e.g., modals, dropdowns, cart drawers). Use `x-data` directly in Blade templates.
3. **Database & ORM:**
   - Always use Eloquent Models.
   - Relationships: `User` has `Role`, `Product` has `Category`, `Order` has `OrderItem`, `Product` has `Inventory`.
   - Use soft deletes for `Product` and `Order`.
4. **Error Handling (CRITICAL):**
   - Wrap DB transactions and complex logic in `try-catch (\Exception $e)`.
   - Log errors: `Log::error('Context message: ' . $e->getMessage());`
   - Never expose raw SQL exceptions to the user.
5. **Role-Based Access Control (RBAC):**
   - Implemented via a dynamic database table (`roles`). Ensure `Role` model is imported and used when managing users.

## Project Structure
- `routes/web.php` & `routes/api.php`
- `app/Http/Controllers/`: `AdminController`, `ProductController`, `StorefrontController`, `CategoryController`.
- `app/Models/`: `Product`, `User`, `Order`, `Inventory`, `Category`, `Role`, `Permission`.
- `resources/views/`: 
  - `layouts/`: `admin.blade.php`, `main.blade.php`.
  - `admin/`: Admin panels.
  - `pages/`: Public storefront.
- `public/css/`: `admin.css`, `style.css`.

## Re-generation / Update Guidelines
If you are asked to regenerate or update this project:
- Respect the existing database schema (look at Models for fields).
- Ensure HTMX endpoints swap HTML effectively using `hx-target` and `hx-swap`.
- Preserve existing vanilla CSS classes.
- Ensure the `opcodesio/log-viewer` library remains in `composer.json` for live error debugging.
