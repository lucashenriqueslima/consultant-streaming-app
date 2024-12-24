<?php

use App\Http\Controllers\GoogleCalendarController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::get('/', [HomeController::class, 'index']);

Route::get('dashboard', App\Livewire\Consultant\Home::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth:candidate', 'verified'])
    ->prefix('candidate')
        ->group(function () {
            Route::get('dashboard', App\Livewire\Candidate\Home::class)
                ->name('candidate.dashboard');

            Route::get('certificates', App\Livewire\Candidate\Certificates::class)
                ->name('candidate.certificates');
});

Route::middleware(['auth:admin', 'verified'])->group(function () {
    Route::prefix('admin/google')->group(function () {
        Route::get('/login', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.login');
        Route::get('/auth-callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google.callback');
    });

    Route::prefix('admin/calendar')->group(function () {
        Route::get('/', [GoogleCalendarController::class, 'index'])->name('calendar.index'); // Listar eventos
        Route::post('/event', [GoogleCalendarController::class, 'createEvent'])->name('calendar.create'); // Criar evento
    });
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/template-email', function () {
    return view('mails.candidate_register_status', ['name' => 'John Doe']);
});

require __DIR__ . '/auth.php';
