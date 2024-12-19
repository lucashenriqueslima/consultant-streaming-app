<?php

use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::get('/', [HomeController::class, 'index']);

Route::get('dashboard', App\Livewire\Consultant\Home::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth:candidate', 'verified'])->group(function () {
    Route::get('candidate/dashboard', App\Livewire\Candidate\Home::class)
        ->name('candidate.dashboard');

    Route::get('candidate/certificates', App\Livewire\Candidate\Certificates::class)
        ->name('candidate.certificates');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/template-email', function () {
    return view('mails.candidate_register_status', ['name' => 'John Doe']);
});

require __DIR__ . '/auth.php';
