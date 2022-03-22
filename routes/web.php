<?php

use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::get('/', [StripeController::class, 'index'])->name('index');
    Route::post('/checkout', [StripeController::class, 'initialize'])->name('pay');
    Route::post('/success', [StripeController::class, 'callback'])->name('callback');
});

Route::prefix('paypal')->name('paypal.')->group(function () {
    Route::get('/', [PaypalController::class, 'index'])->name('index');
    Route::post('/checkout', [PaypalController::class, 'initialize'])->name('pay');
    Route::get('/success', [PaypalController::class, 'success'])->name('success');
    Route::get('/cancelled', [PaypalController::class, 'cancelled'])->name('cancelled');
});

Route::get('/paystack', [PaystackController::class, 'initialize'])->name('paystack.pay');
Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');