<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BungalowController as AdminBungalowController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\BungalowController as CustomerBungalowController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/bungalows', [CustomerBungalowController::class, 'index'])->name('bungalows.index');
Route::get('/bungalows/{bungalow}', [CustomerBungalowController::class, 'show'])->name('bungalows.show');

Route::middleware('auth')->group(function () {
    Route::post('/bungalows/{bungalow}/bookings', [CustomerBookingController::class, 'store'])->name('customer.bookings.store');
    Route::get('/my-bookings', [CustomerBookingController::class, 'index'])->name('customer.bookings.index');
    Route::patch('/my-bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('customer.bookings.cancel');
    Route::get('/profile', [ProfileController::class, 'show'])->name('customer.profile.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('bungalows', AdminBungalowController::class)->except('show');
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/paid', [AdminPaymentController::class, 'markPaid'])->name('payments.paid');
    Route::patch('payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');
});

require __DIR__.'/auth.php';
