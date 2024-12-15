<?php

// use App\Http\Controllers\ListNumberController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', 'ListNumberController@index')->name('index');

Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/cart/view', [CartController::class, 'viewCart']);
Route::post('/cart/checkout', [CartController::class, 'checkout']);
Route::get('/cart/items', [CartController::class, 'getCartItems']);
Route::post('/cart/clear', [CartController::class, 'clearCart']);
Route::post('/cart/clear-item', [CartController::class, 'clearCartItem']);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'index']);
    Route::post('/exportExcel', [ExcelController::class, 'exportExcel']);
    Route::post('/exportRemainingExcel', [ExcelController::class, 'exportRemainingExcel']);
    Route::post('/add-purchase', [DashboardController::class, 'addPurchase']);
    Route::post('/add-winning', [DashboardController::class, 'addWinning']);
    Route::post('/edit-purchase/{id}', [DashboardController::class, 'editPurchase']);
    Route::delete('/delete-purchase/{id}', [DashboardController::class, 'deletePurchase']);
    Route::post('/edit-winning/{id}', [DashboardController::class, 'editWinning']);
    Route::delete('/delete-winning/{id}', [DashboardController::class, 'deleteWinning']);
    Route::post('/update-price', [DashboardController::class, 'updatePrice']);
});
