<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\ReviewController;

// Redirect home to explore page
Route::get('/', function () {
    return redirect()->route('fields.index');
});

// Explore fields (accessible to all, guest and auth users)
Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
Route::get('/fields/{field}', [FieldController::class, 'show'])->name('fields.show');

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Player routes
    Route::get('/bookings/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Owner routes
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/owner/calendar-bookings', [OwnerDashboardController::class, 'calendarBookings'])->name('owner.calendar.bookings');
    Route::get('/owner/fields/create', [OwnerDashboardController::class, 'createField'])->name('owner.fields.create');
    Route::post('/owner/fields', [OwnerDashboardController::class, 'storeField'])->name('owner.fields.store');
    Route::get('/owner/fields/{field}/edit', [OwnerDashboardController::class, 'editField'])->name('owner.fields.edit');
    Route::put('/owner/fields/{field}', [OwnerDashboardController::class, 'updateField'])->name('owner.fields.update');
    Route::delete('/owner/fields/{field}', [OwnerDashboardController::class, 'destroyField'])->name('owner.fields.destroy');
});
