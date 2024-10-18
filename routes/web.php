<?php

use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::get('/', [HomeController::class, 'index']);

Route::get('dashboard', App\Livewire\Home::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

require __DIR__ . '/auth.php';
