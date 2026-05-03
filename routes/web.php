<?php

use Illuminate\Support\Facades\Route;

//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider and all of them will
//| be assigned to the "web" middleware group. Make something great!
//|
//*/

/*دفع طلب الخدمة*/
Route::get('/payment-page/{order_number}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'index'])->name('user.payment');
Route::get('/payment-page/{number}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'callback'])->name('payment.callback');

/*دفع الباقة*/
Route::get('/payment-package/{package_id}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'package_payment_index'])->name('user.payment_package');
Route::get('/payment-package/{package_id}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'package_callback'])->name('payment.package_callback');

/*تجديد الباقة*/
Route::get('/payment-renewal-subscribe/{package_id}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'renewal_package_payment_index'])->name('user.renewal_payment_package');
Route::get('/payment-renewal-subscribe/{package_id}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'renewal_package_callback'])->name('payment.renewal_package_callback');


require __DIR__ . '/dashboard_cleaned.php';
