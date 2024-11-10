<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\Api\ModuleController as ApiModuleController;

Route::get('/', function () {
    return view('welcome');
});

// web route to view modules and history
Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
Route::get('/modules/{id}/history', [ModuleController::class, 'history'])->name('modules.history');


// create new module
Route::get('/modules/add', [ModuleController::class, 'add'])->name('modules.add');
Route::post('/modules', [ModuleController::class, 'create'])->name('modules.create');

// Delete a module
Route::delete('/modules/delete/{id}', [ModuleController::class, 'destroy'])->name('modules.delete');


// API route to fetch modules data
Route::get('/api/modules', [ApiModuleController::class, 'getModuleData']);
Route::get('/api/modules/{id}/history', [ModuleController::class, 'getHistory']);



// Only for development and testing
Route::delete('/clear-database', [ModuleController::class, 'clearDatabase']);
