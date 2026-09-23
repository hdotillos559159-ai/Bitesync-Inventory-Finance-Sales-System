<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BiteSync — Finance module routes
|--------------------------------------------------------------------------
| Add these into your project's routes/web.php (or require this file from it).
*/

// Tabbed dashboard: Sales / Expenses / Purchases, last 7 days.
Route::redirect('/', '/finance');

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'create'])->name('login');
	Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
	Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
	Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.index');
	Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
	Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

	// Full CRUD for each entity (index, create, store, edit, update, destroy).
	Route::resource('sales', SaleController::class)->except(['show']);
	Route::resource('purchases', PurchaseController::class)->except(['show']);
	Route::resource('expenses', ExpenseController::class)->except(['show']);
});
