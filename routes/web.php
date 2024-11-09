<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;

Route::get('/', function () {
    return view('welcome');
});

// web route to view modules and history
Route::get('/modules', [ModuleController::class, 'index']);
Route::get('/modules/{id}/history', [ModuleController::class, 'history']);


// create new module
Route::get('/modules/add', [ModuleController::class, 'add'])->name('modules.add');
Route::post('/modules', [ModuleController::class, 'create'])->name('modules.create');
