<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;


Route::get('/checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
