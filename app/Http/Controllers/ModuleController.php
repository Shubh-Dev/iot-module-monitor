<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleHistory;
use Illuminate\Support\Facades\Log;
use Faker\Factory as Faker;
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

    public function add()
    {
        return view('modules.add');
    }

    public function create(Request $request)
    {
        // validate inputs
        $validated = $request->validate([
            'name' => 'required | string | max:225',
            'type' => 'required | string | max:225',
            'status' => 'required | string | in:active, inactive, malfunction',
        ]);

        $faker = Faker::create();

        // Fill in missing fields with Faker-generated values
        $moduleData = array_merge($validated, [
            'measured_value' => $faker->randomFloat(2, 10, 100),
            'operating_time' => $faker->numberBetween(1, 100),
            'data_sent_count' => $faker->numberBetween(1, 1000),
        ]);

        try {

            // create a module
            Module::create($moduleData);

            // redirect with a success message
            return redirect()->route('modules.index')->with('success', 'Module created successfully');
        } catch (\Exception $e) {
            Log::error("Error creating module: " . $e->getMessage());
        }
    }
}
