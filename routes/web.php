<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::get('/', [HomeController::class, 'index']);

Route::get('dashboard', App\Livewire\Home::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', App\Livewire\Home::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
