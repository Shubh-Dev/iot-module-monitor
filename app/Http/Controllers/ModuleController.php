<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class ModuleController extends Controller
{
    public function index()
    {
        try {
            $modules = Module::all();
            $history = ModuleHistory::orderBy('created_at', 'desc')->get();

            return view('modules.index', compact('modules', 'history'));
        } catch (\Exception $e) {
            Log::error('Error fetching modules: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while fetching modules.'], 500);
        }
    }

    // public function history($id)
    // {
    //     $histories = ModuleHistory::Where('module_id', $id)->get();
    //     return view('modules.history', compact('histories'));
    // }

    public function history($id)
    {
        try {
            $histories = ModuleHistory::where('module_id', $id)->orderBy('created_at', 'desc')->get();
            return view('modules.history', compact('histories'));
        } catch (\Exception $e) {
            Log::error('Error fetching modules data for API: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch modules data.'], 500);
        }
    }

    public function getModulesData()
    {
        try {
            // Fetch all modules or apply any necessary filtering
            $modules = Module::all();

            // Return data as JSON
            return response()->json($modules);
        } catch (\Exception $e) {
            Log::error('Error fetching modules data for API: ' . $e->getMessage());

            // Optionally, return an error response
            return response()->json(['error' => 'Unable to fetch modules data.'], 500);
        }
    }
}
