<?php
// File: routes/web.php (UPDATE LENGKAP)

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\UserController as OwnerUserController;
use App\Http\Controllers\Owner\CategoryController as OwnerCategoryController;
use App\Http\Controllers\Owner\ProductController as OwnerProductController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Owner\InventoryController as OwnerInventoryController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Owner\SettingController as OwnerSettingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (dari Breeze)
require __DIR__ . '/auth.php';

// Route setelah login - redirect berdasarkan role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isOwner()) {
        return redirect()->route('owner.dashboard');
    } elseif ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isCustomer()) {
        return redirect()->route('customer.dashboard');
    }

    abort(403, 'Role tidak dikenali');
})->middleware(['auth'])->name('dashboard');

// ============================================================
// OWNER ROUTES
// ============================================================
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // User Management (Owner can manage all users)
    Route::resource('/users', OwnerUserController::class);
    Route::post('/users/{user}/toggle-status', [OwnerUserController::class, 'toggleStatus'])
        ->name('users.toggleStatus');

    // Category Management
    Route::resource('/categories', OwnerCategoryController::class);
    Route::post('/categories/{category}/toggle-status', [OwnerCategoryController::class, 'toggleStatus'])
        ->name('categories.toggleStatus');

    // Product Management
    Route::resource('/products', OwnerProductController::class);
    Route::post('/products/{product}/toggle-status', [OwnerProductController::class, 'toggleStatus'])
        ->name('products.toggleStatus');
    Route::post('/products/{product}/toggle-featured', [OwnerProductController::class, 'toggleFeatured'])
        ->name('products.toggleFeatured');

    // Inside Owner group
    Route::get('/inventory', [OwnerInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/alerts', [OwnerInventoryController::class, 'alerts'])->name('inventory.alerts');
    Route::get('/inventory/logs', [OwnerInventoryController::class, 'logs'])->name('inventory.logs');
    Route::get('/inventory/{product}/adjust', [OwnerInventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/{product}/adjust', [OwnerInventoryController::class, 'processAdjustment'])->name('inventory.processAdjustment');
    Route::get('/inventory/bulk-adjust', [OwnerInventoryController::class, 'bulkAdjust'])->name('inventory.bulkAdjust');
    Route::post('/inventory/bulk-adjust', [OwnerInventoryController::class, 'processBulkAdjustment'])->name('inventory.processBulkAdjustment');
    Route::get('/inventory/export', [OwnerInventoryController::class, 'export'])->name('inventory.export');

    // Order Approval
    Route::prefix('orders/approval')->name('orders.approval.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'show'])->name('show');
        Route::post('/{order}/approve', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'approve'])->name('approve');
        Route::post('/{order}/reject', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'reject'])->name('reject');
    });

    // Purchase Order Approval Routes
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\PurchaseOrderApprovalController::class, 'index'])
            ->name('index');
        Route::get('/{purchaseOrder}', [\App\Http\Controllers\Owner\PurchaseOrderApprovalController::class, 'show'])
            ->name('show');
        Route::post('/{purchaseOrder}/approve', [\App\Http\Controllers\Owner\PurchaseOrderApprovalController::class, 'approve'])
            ->name('approve');
        Route::post('/{purchaseOrder}/reject', [\App\Http\Controllers\Owner\PurchaseOrderApprovalController::class, 'reject'])
            ->name('reject');
    });

    // Settings Routes
    Route::get('/settings', [OwnerSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [OwnerSettingController::class, 'updateGeneral'])->name('settings.updateGeneral');
    Route::post('/settings/system', [OwnerSettingController::class, 'updateSystem'])->name('settings.updateSystem');
    Route::post('/settings/landing', [OwnerSettingController::class, 'updateLanding'])->name('settings.updateLanding');
    Route::post('/settings/delete-image', [OwnerSettingController::class, 'deleteImage'])->name('settings.deleteImage');

    // Reports
    // Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    // Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('/users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
        ->name('users.toggleStatus');

    // Category Management
    Route::resource('/categories', AdminCategoryController::class);
    Route::post('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])
        ->name('categories.toggleStatus');

    // Product Management
    Route::resource('/products', AdminProductController::class);
    Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])
        ->name('products.toggleStatus');
    Route::post('/products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])
        ->name('products.toggleFeatured');

    // Inside admin group
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/alerts', [AdminInventoryController::class, 'alerts'])->name('inventory.alerts');
    Route::get('/inventory/logs', [AdminInventoryController::class, 'logs'])->name('inventory.logs');
    Route::get('/inventory/{product}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/{product}/adjust', [AdminInventoryController::class, 'processAdjustment'])->name('inventory.processAdjustment');
    Route::get('/inventory/bulk-adjust', [AdminInventoryController::class, 'bulkAdjust'])->name('inventory.bulkAdjust');
    Route::post('/inventory/bulk-adjust', [AdminInventoryController::class, 'processBulkAdjustment'])->name('inventory.processBulkAdjustment');
    Route::get('/inventory/export', [AdminInventoryController::class, 'export'])->name('inventory.export');

    // Receive Purchase Order Routes (HARUS sebelum resource routes)
    Route::prefix('purchase-orders/receive')->name('purchase-orders.receive.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReceivePurchaseOrderController::class, 'index'])
            ->name('index');
        Route::get('/{purchaseOrder}', [\App\Http\Controllers\Admin\ReceivePurchaseOrderController::class, 'show'])
            ->name('show');
        Route::post('/{purchaseOrder}', [\App\Http\Controllers\Admin\ReceivePurchaseOrderController::class, 'store'])
            ->name('store');
    });

    // Purchase Order Routes
    Route::resource('purchase-orders', \App\Http\Controllers\Admin\PurchaseOrderController::class);
    Route::post('purchase-orders/{purchaseOrder}/submit', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'submit'])
        ->name('purchase-orders.submit');

    // Payment Verification
    // Route::get('/payments/verification', [PaymentController::class, 'verification'])->name('payments.verification');
    // Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
});

// ============================================================
// CUSTOMER ROUTES
// ============================================================
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Product Catalog (akan dibuat di fase berikutnya)
    // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Shopping Cart
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::put('/update/{cart}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cart}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/update-prices', [CartController::class, 'updatePrices'])->name('updatePrices');
    Route::get('/count', [CartController::class, 'count'])->name('count');

    // Checkout
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
        Route::post('/{order}/upload-payment', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPayment'])->name('uploadPayment');
        Route::post('/{order}/cancel', [\App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('cancel');
    });
});

// ============================================================
// CATALOG ROUTES (Accessible by ALL authenticated users)
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Product Catalog
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/products/{product}/quick-view', [CatalogController::class, 'quickView'])->name('catalog.quickView');
});

// ============================================================
// PROFILE ROUTES (semua role bisa akses)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
