<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;

// Api controller import
use App\Http\Controllers\Api\ModuleController as ApiModuleController;

Route::get('/', function () {
    return view('welcome');
});

// web route to view modules and history
Route::get('/modules', [ModuleController::class, 'index']);
Route::get('/modules/{id}/history', [ModuleController::class, 'history']);

// API route to fetch modules data
Route::get('/api/modules', [ApiModuleController::class, 'getModuleData']);
