<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/modules', [ModuleController::class, 'index']);
Route::get('/history', [ModuleController::class, 'history']);
