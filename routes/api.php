<?php

use App\Http\Controllers\LessonController;
use App\Models\AccessLog;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return [
        'status' => 'ok'
    ];
});
