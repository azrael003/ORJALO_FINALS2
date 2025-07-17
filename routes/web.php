<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

// Public route (homepage)
Route::get('/', function () {
    return view('welcome');
});

// Guest-only routes (registration)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.post');
});

// Public route for viewing a specific booking
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings CRUD (excluding 'show' because it's public)
    Route::resource('bookings', BookingViewController::class)->except(['show']);

    // Profile management (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    // Users listing route
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // If you want to enable full CRUD for users:
    // Route::resource('users', UserController::class);
});

// Authentication routes (login, logout, password reset, etc.)
require __DIR__.'/auth.php';
