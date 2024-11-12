<?php

use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\StripePaymentController;
use App\Http\Controllers\API\V1\StripeWebhookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prdouct', [ProductController::class, ['index']])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Routing:
Route::get('/products/{product}', [ProductController::class, 'show']);

// Middleware
Route::get('/products', [ProductController::class, 'index'])->middleware('log.request');

// Authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product Routes
    Route::resource('/product', ProductController::class);
    Route::get('/product/{id}/delete', [ProductController::class, 'destroy']);

    // Stripe Routes
    Route::get('/payment', [StripePaymentController::class, 'showForm'])->name('payment.show');
    Route::post('/payment', [StripePaymentController::class, 'payment'])->name('payment.submission');
    Route::get('/payment/success', function () {
        return view('payment.success');
    })->name('payment.success');
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
});

require __DIR__.'/auth.php';
