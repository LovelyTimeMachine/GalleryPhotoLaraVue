<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PhotoWallpaperController;
use App\Http\Controllers\AdminController; // Ensure you're using this if you're managing users with an AdminController
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard Route - accessible by everyone but loads data from the controller
Route::get('/dashboard', [PhotoWallpaperController::class, 'index'])->name('dashboard');

// Profile Settings Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-picture', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/user-management', [ProfileController::class, 'index'])->name('user.management'); // Ensure this route is protected
    Route::get('/users', [ProfileController::class, 'getUsers'])->name('users.index');
    Route::delete('/users/{id}', [ProfileController::class, 'adminDestroy'])->name('users.destroy');


});


// Authenticated and Verified Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Additional Settings Page
    Route::get('/settings', [ProfileController::class, 'index'])->name('settings');

    // Photo Routes
    Route::get('/photos', [PhotoWallpaperController::class, 'index'])->name('photos.index');
    Route::get('/photos/create', [PhotoWallpaperController::class, 'create'])->name('photos.create');
    Route::post('/photos', [PhotoWallpaperController::class, 'store'])->name('photos.store');
    Route::get('/photos/{id}', [PhotoWallpaperController::class, 'showPhoto'])->name('photos.show');
    Route::post('/photos/{photo}/like', [PhotoWallpaperController::class, 'likePhoto'])->name('photos.like');
    Route::put('/photos/{photo}', [PhotoWallpaperController::class, 'update'])->name('photos.update');
    Route::delete('/photos/{id}', [PhotoWallpaperController::class, 'destroy'])->name('photos.destroy');

    // Comment Route
    Route::post('/photos/{id}/comments', [CommentController::class, 'store'])->name('comments.store');

    });

require __DIR__.'/auth.php';
