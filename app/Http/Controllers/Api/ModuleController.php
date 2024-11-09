<?php

namespace App\Http\Controllers\Api;

use App\Models\Module;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function getModuleData()
    {
        try {
            $modules = Module::all();

            Log::info('Number of modules fetched: ' . $modules->count());
            // Return data as JSON response
            return response()->json($modules);
        } catch (\Exception $e) {
            Log::error('Error fetching module data: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
