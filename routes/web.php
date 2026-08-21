<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/purchase-orders/scan', [PurchaseOrderController::class, 'scan'])->name('purchase-orders.scan');
    Route::get('/purchase-orders/{purchaseOrder}/shipping', [PurchaseOrderController::class, 'shipping'])->name('purchase-orders.shipping');
    Route::patch('/purchase-orders/{purchaseOrder}/shipping', [PurchaseOrderController::class, 'updateShipping'])->name('purchase-orders.shipping.update');
    Route::get('/purchase-orders/{purchaseOrder}/payment', [PurchaseOrderController::class, 'payment'])->name('purchase-orders.payment');
    Route::patch('/purchase-orders/{purchaseOrder}/payment', [PurchaseOrderController::class, 'updatePayment'])->name('purchase-orders.payment.update');
    Route::get('/customers/search', [CustomerController::class, 'search']);
    Route::get('/purchase-orders/confirm', [PurchaseOrderController::class, 'confirm'])->name('purchase-orders.confirm');
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
