<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'pages.consultant.auth.login')
        ->name('login');
});

Route::prefix('candidate')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', function() {
            return Redirect::route('candidate.login');
        });

        Volt::route('login', 'pages.candidate.auth.login')
            ->name('candidate.login');

        Route::get('register', App\Livewire\Candidate\Register::class)
            ->name('candidate.register');
    });
});
