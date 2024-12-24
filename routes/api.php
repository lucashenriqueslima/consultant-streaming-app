<?php

use App\Http\Controllers\{ConsultantCandidateController, LessonController, ContatoController};
use App\Models\{AccessLog, User, UserProgress};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return User::all();
});

Route::get('/user-progress', function (Request $request) {
    return UserProgress::all();
});

Route::get('/access-logs', function (Request $request) {
    return AccessLog::all();
});

Route::post('/contact', [ContatoController::class, 'send']);

// Route::post('/consult-puxa-capivara', [ConsultantCandidateController::class, 'beginConsultApiPuxaCapivara']);
    // ->middleware(['auth', 'verified']);

Route::post('/upload', [LessonController::class, 'upload']);
Route::delete('/revert', [LessonController::class, 'revert']);
