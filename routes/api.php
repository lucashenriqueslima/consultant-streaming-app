<?php

use App\Http\Controllers\{LessonController, ContatoController};
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

Route::post('/upload', [LessonController::class, 'upload']);
Route::delete('/revert', [LessonController::class, 'revert']);
