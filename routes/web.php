<?php

use App\Http\Controllers\ContatoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/termos-de-uso', [HomeController::class, 'termos'])->name('termos-de-uso');
Route::get('/privacidade', [HomeController::class, 'privacidade'])->name('privacidade');
Route::get('/entre-em-contato', [HomeController::class, 'contato'])->name('entre-em-contato');
Route::get('/busca', [HomeController::class, 'busca'])->name('busca');
Route::post('/contato/send', [ContatoController::class, 'send'])->name('contato.send');
