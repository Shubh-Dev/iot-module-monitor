<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModuleController;


// Define routes for API functionality
Route::get('/modules', [ModuleController::class, 'getModuleData']);

