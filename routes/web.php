<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\TweetLikeController;
use Illuminate\Support\Facades\Route;

// Home & Feed
Route::get('/', [TweetController::class, 'index'])->name('home');
Route::get('/explore', [TweetController::class, 'explore'])->name('explore');

// Authentication routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Demo 1-Click Login (convenient testing)
Route::post('/demo-login/{username}', [AuthController::class, 'demoLogin'])->name('demo-login');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tweet actions
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::get('/tweets/{tweet}/edit', [TweetController::class, 'edit'])->name('tweets.edit');
    Route::put('/tweets/{tweet}', [TweetController::class, 'update'])->name('tweets.update');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');
    Route::post('/tweets/{tweet}/like', [TweetLikeController::class, 'toggle'])->name('tweets.like');

    // Follow / Unfollow
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');

    // Profile settings
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// User profile (@username)
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');
