<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Storefront
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/category/{slug}', [StorefrontController::class, 'category'])->name('category');
Route::get('/cart/drawer', [StorefrontController::class, 'getCartDrawer'])->name('cart.drawer');
Route::post('/cart/add', [StorefrontController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{productId}', [StorefrontController::class, 'updateCartQuantity'])->name('cart.update');
Route::post('/cart/remove/{productId}', [StorefrontController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::get('/product/{slug}', [StorefrontController::class, 'productDetail'])->name('product.detail');
Route::get('/buy/{slug}', [StorefrontController::class, 'quickBuy'])->name('product.buy');
Route::post('/quick-checkout', [StorefrontController::class, 'quickCheckout'])->name('quick.checkout');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin (auth protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Products
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'adminCreate'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'adminStore'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'adminEdit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'adminUpdate'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'adminDelete'])->name('admin.products.delete');

    // Orders
    Route::get('/orders', [AdminController::class, 'adminOrders'])->name('admin.orders.index');
    Route::get('/orders/{id}', [AdminController::class, 'adminOrderDetail'])->name('admin.orders.detail');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::get('/orders/{id}/invoice', [AdminController::class, 'invoice'])->name('admin.orders.invoice');

    // Inventory
    Route::get('/inventory', [AdminController::class, 'adminInventory'])->name('admin.inventory.index');

    // Users
    Route::get('/users', [AdminController::class, 'adminUsers'])->name('admin.users.index');

    // Reports
    Route::get('/reports', [AdminController::class, 'adminReports'])->name('admin.reports.index');

    // Categories
    Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'adminCreate'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'adminStore'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'adminEdit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'adminUpdate'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'adminDelete'])->name('admin.categories.delete');

    // Resources
    Route::get('/resources/features', [AdminController::class, 'features'])->name('admin.resources.features');
    Route::get('/resources/brochure', [AdminController::class, 'brochure'])->name('admin.resources.brochure');
    Route::get('/resources/guide', [AdminController::class, 'adminGuide'])->name('admin.resources.guide');
});
