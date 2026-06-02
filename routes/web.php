<?php

use App\Livewire\Dashboard;
use App\Livewire\Reports;
use App\Livewire\Settings;
use App\Livewire\HealingTracker;
use App\Http\Controllers\AuthController;

// ── Splash screen (public)
Route::get('/splash', function () {
    return view('splash');
})->name('splash');

// ── Auth routes (public)
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ── Protected routes (login required)
Route::middleware('auth')->group(function () {
    Route::get('/',         Dashboard::class)->name('dashboard');
    Route::get('/reports',  Reports::class)->name('reports');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/healing',  HealingTracker::class)->name('healing');
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
});