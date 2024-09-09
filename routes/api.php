<?php

use App\Models\AccessLog;
use App\Models\User;
use App\Models\UserProgress;
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
