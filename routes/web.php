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
use App\Http\Controllers\Owner\LandingSettingController as OwnerLandingSettingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// ============================================================
// LANGUAGE SWITCH ROUTE
// ============================================================
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// ============================================================
// PUBLIC LANDING PAGE ROUTES
// ============================================================
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/landing/product/{id}', [LandingController::class, 'getProductDetail'])->name('landing.product.detail');
Route::get('/landing/products', [LandingController::class, 'getProductsByCategory'])->name('landing.products.category');
Route::get('/tracking', [LandingController::class, 'tracking'])->name('tracking.jne');

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

    // Supplier Management
    Route::resource('/suppliers', \App\Http\Controllers\Owner\SupplierController::class);
    Route::post('/suppliers/{supplier}/toggle-status', [\App\Http\Controllers\Owner\SupplierController::class, 'toggleStatus'])
        ->name('suppliers.toggleStatus');

    // Order Approval
    Route::prefix('orders/approval')->name('orders.approval.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'show'])->name('show');
        Route::post('/{order}/approve', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'approve'])->name('approve');
        Route::post('/{order}/reject', [\App\Http\Controllers\Owner\OrderApprovalController::class, 'reject'])->name('reject');
    });

    // Order History
    Route::prefix('orders/history')->name('orders.history.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\OrderHistoryController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Owner\OrderHistoryController::class, 'show'])->name('show');
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

    // Purchase Order History Routes
    Route::prefix('purchase-orders/history')->name('purchase-orders.history.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\PurchaseOrderHistoryController::class, 'index'])->name('index');
        Route::get('/{purchaseOrder}', [\App\Http\Controllers\Owner\PurchaseOrderHistoryController::class, 'show'])->name('show');
    });

    // Payment Verification (Owner)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'index'])
            ->name('index');
        Route::get('/{payment}', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'show'])
            ->name('show');
        Route::post('/{payment}/verify', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'verify'])
            ->name('verify');
        Route::post('/{payment}/reject', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'reject'])
            ->name('reject');
        Route::post('/{payment}/process-order', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'processOrder'])
            ->name('processOrder');
        Route::post('/{payment}/ship-order', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'shipOrder'])
            ->name('shipOrder');
        Route::post('/{payment}/complete-order', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'completeOrder'])
            ->name('completeOrder');
        Route::post('/{payment}/update-tracking', [\App\Http\Controllers\Owner\PaymentVerificationController::class, 'updateTrackingNumber'])
            ->name('updateTracking');
    });

    // Settings Routes
    Route::get('/settings', [OwnerSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [OwnerSettingController::class, 'updateGeneral'])->name('settings.updateGeneral');
    Route::post('/settings/system', [OwnerSettingController::class, 'updateSystem'])->name('settings.updateSystem');
    Route::post('/settings/landing', [OwnerSettingController::class, 'updateLanding'])->name('settings.updateLanding');
    Route::post('/settings/delete-image', [OwnerSettingController::class, 'deleteImage'])->name('settings.deleteImage');

    // ============================================================
    // LANDING PAGE SETTINGS ROUTES
    // ============================================================
    Route::prefix('landing-settings')->name('landing-settings.')->group(function () {
        Route::get('/', [OwnerLandingSettingController::class, 'index'])->name('index');

        // Slides Management
        Route::post('/slides', [OwnerLandingSettingController::class, 'storeSlide'])->name('slides.store');
        Route::put('/slides/{slide}', [OwnerLandingSettingController::class, 'updateSlide'])->name('slides.update');
        Route::delete('/slides/{slide}', [OwnerLandingSettingController::class, 'destroySlide'])->name('slides.destroy');

        // Partners Management
        Route::post('/partners', [OwnerLandingSettingController::class, 'storePartner'])->name('partners.store');
        Route::put('/partners/{partner}', [OwnerLandingSettingController::class, 'updatePartner'])->name('partners.update');
        Route::delete('/partners/{partner}', [OwnerLandingSettingController::class, 'destroyPartner'])->name('partners.destroy');

        // Promos Management
        Route::post('/promos', [OwnerLandingSettingController::class, 'storePromo'])->name('promos.store');
        Route::put('/promos/{promo}', [OwnerLandingSettingController::class, 'updatePromo'])->name('promos.update');
        Route::delete('/promos/{promo}', [OwnerLandingSettingController::class, 'destroyPromo'])->name('promos.destroy');

        // Section Settings
        Route::post('/sections', [OwnerLandingSettingController::class, 'updateSections'])->name('sections.update');
        Route::post('/sections/delete-image', [OwnerLandingSettingController::class, 'deleteSectionImage'])->name('sections.delete-image');
    });

    // ============================================================
    // FINANCIAL MANAGEMENT ROUTES
    // ============================================================

    // Financial Dashboard & Logs
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Owner\FinancialController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/', [\App\Http\Controllers\Owner\FinancialController::class, 'index'])
            ->name('index');

        Route::get('/expense/create', [\App\Http\Controllers\Owner\FinancialController::class, 'createExpense'])
            ->name('expense.create');

        Route::post('/expense', [\App\Http\Controllers\Owner\FinancialController::class, 'storeExpense'])
            ->name('expense.store');

        Route::delete('/{financialLog}', [\App\Http\Controllers\Owner\FinancialController::class, 'destroy'])
            ->name('destroy');
    });

    // ============================================================
    // INVOICE ROUTES
    // ============================================================
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/{order}/generate', [\App\Http\Controllers\Owner\InvoiceController::class, 'generate'])
            ->name('generate');

        Route::get('/{order}/preview', [\App\Http\Controllers\Owner\InvoiceController::class, 'preview'])
            ->name('preview');

        Route::get('/{order}/download', [\App\Http\Controllers\Owner\InvoiceController::class, 'download'])
            ->name('download');

        Route::post('/{order}/regenerate', [\App\Http\Controllers\Owner\InvoiceController::class, 'regenerate'])
            ->name('regenerate');

        Route::get('/{order}/exists', [\App\Http\Controllers\Owner\InvoiceController::class, 'exists'])
            ->name('exists');
    });

    // ============================================================
    // REPORTS ROUTES
    // ============================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        // Financial Reports
        Route::get('/financial', [\App\Http\Controllers\Owner\ReportController::class, 'financial'])
            ->name('financial');

        Route::get('/financial/export/excel', [\App\Http\Controllers\Owner\ReportController::class, 'exportFinancialExcel'])
            ->name('financial.export.excel');

        Route::get('/financial/export/pdf', [\App\Http\Controllers\Owner\ReportController::class, 'exportFinancialPdf'])
            ->name('financial.export.pdf');

        // Inventory Reports
        Route::get('/inventory', [\App\Http\Controllers\Owner\ReportController::class, 'inventory'])
            ->name('inventory');

        Route::get('/inventory/export/excel', [\App\Http\Controllers\Owner\ReportController::class, 'exportInventoryExcel'])
            ->name('inventory.export.excel');

        Route::get('/inventory/export/pdf', [\App\Http\Controllers\Owner\ReportController::class, 'exportInventoryPdf'])
            ->name('inventory.export.pdf');
    });
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

    // Supplier Management
    Route::resource('/suppliers', \App\Http\Controllers\Admin\SupplierController::class);
    Route::post('/suppliers/{supplier}/toggle-status', [\App\Http\Controllers\Admin\SupplierController::class, 'toggleStatus'])
        ->name('suppliers.toggleStatus');

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

    // Order Approval (Admin) - Same as Owner
    Route::prefix('orders/approval')->name('orders.approval.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderApprovalController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Admin\OrderApprovalController::class, 'show'])->name('show');
        Route::post('/{order}/approve', [\App\Http\Controllers\Admin\OrderApprovalController::class, 'approve'])->name('approve');
        Route::post('/{order}/reject', [\App\Http\Controllers\Admin\OrderApprovalController::class, 'reject'])->name('reject');
    });

    // Order History (Admin)
    Route::prefix('orders/history')->name('orders.history.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderHistoryController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Admin\OrderHistoryController::class, 'show'])->name('show');
    });

    // Purchase Order History (Admin)
    Route::prefix('purchase-orders/history')->name('purchase-orders.history.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PurchaseOrderHistoryController::class, 'index'])->name('index');
        Route::get('/{purchaseOrder}', [\App\Http\Controllers\Admin\PurchaseOrderHistoryController::class, 'show'])->name('show');
    });

    // Payment Verification (Admin)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'index'])
            ->name('index');
        Route::get('/{payment}', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'show'])
            ->name('show');
        Route::post('/{payment}/verify', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'verify'])
            ->name('verify');
        Route::post('/{payment}/reject', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'reject'])
            ->name('reject');
        Route::post('/{payment}/process-order', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'processOrder'])
            ->name('processOrder');
        Route::post('/{payment}/ship-order', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'shipOrder'])
            ->name('shipOrder');
        Route::post('/{payment}/complete-order', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'completeOrder'])
            ->name('completeOrder');
        Route::post('/{payment}/update-tracking', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'updateTrackingNumber'])
            ->name('updateTracking');
    });
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

    // Order History (HARUS sebelum orders/{order} agar tidak konflik)
    Route::get('/orders/history', [\App\Http\Controllers\Customer\OrderHistoryController::class, 'index'])->name('orders.history.index');

    // Orders - menggunakan resource routes dengan implicit binding
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
        Route::post('/{order}/upload-payment', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPayment'])->name('uploadPayment');
        Route::post('/{order}/cancel', [\App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{order}/confirm-received', [\App\Http\Controllers\Customer\OrderController::class, 'confirmReceived'])->name('confirmReceived');
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
// INVOICE ROUTES (Accessible by ALL authenticated users)
// ============================================================
Route::middleware(['auth'])->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/{order}/preview', [\App\Http\Controllers\InvoiceController::class, 'preview'])
        ->name('preview');
    Route::get('/{order}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])
        ->name('download');
});

// ============================================================
// PROFILE ROUTES (semua role bisa akses)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
