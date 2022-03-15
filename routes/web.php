<?php

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

Route::get('/stripe', [StripeController::class, 'index']);
Route::post('/stripe/checkout', [StripeController::class, 'initialize'])->name('stripe.pay');
Route::post('/stripe/success', [StripeController::class, 'callback'])->name('stripe.callback');

Route::get('/paystack', [PaystackController::class, 'initialize'])->name('paystack.pay');
Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');