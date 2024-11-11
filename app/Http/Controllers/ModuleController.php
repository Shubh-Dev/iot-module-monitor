<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;



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


    public function history($moduleId)
    {
        try {
            $module = Module::findOrFail($moduleId);
            // Check if no history data is found
            $history = $module->moduleHistory()->orderBy('recorded_at', 'desc')->get();

            if ($history->isEmpty()) {
                // Return a custom "no data" view if no history records are found
                return view('modules.no-data', compact('module'));
            }

            return view('modules.history', compact('module', 'history'));
        } catch (\Exception $e) {
            Log::error('Error fetching modules data: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch modules data.'], 500);
        }
    }


    public function getHistory($id)
    {
        try {
            $history = ModuleHistory::where('module_id', $id)
                ->orderBy('created_at', 'desc')
                ->take(100) // Limit the data to avoid large payloads
                ->get();

            return response()->json($history);
        } catch (\Exception $e) {
            Log::error('Error fetching History data for API: ' . $e->getMessage());
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
            'status' => 'required | string',
        ]);

        $faker = Faker::create();

        // Fill in missing fields with Faker-generated values
        $moduleData = array_merge($validated, [
            'measured_value' => $faker->randomFloat(2, 10, 100),
            'operating_time' => $faker->numberBetween(1, 100),
            'data_sent_count' => $faker->numberBetween(1, 1000),
        ]);

        DB::beginTransaction();

        try {
            $module = Module::create($moduleData);

            ModuleHistory::create([
                'module_id' => $module->id,  // The foreign key to the modules table
                'measured_value' => $module->measured_value,
                'status' => $module->status,
                'operating_time' => $module->operating_time,
                'data_sent_count' => $module->data_sent_count,
                'recorded_at' => now(),  // Timestamp of creation
            ]);

            DB::commit();  // Commit the transaction
            return redirect()->route('modules.index')->with('success', 'Module created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error creating module: " . $e->getMessage());
            return redirect()->route('modules.index')->with('error', 'Failed to create module');
        }
    }


    public function destroy($id)
    {
        
        $module = Module::findOrFail($id);

        try {
            $module->delete();
            Log::error("Module with id: " . $id . "Deleted");
            return response()->json(['success' => 'Module deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Cannot Delete Module: " . $e->getMessage());
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $module = Module::find($id);

        try {
            if ($module) {
                $module->status = $request->input('status');
                $module->save();
                toastr()->success('Module status updated');
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            toastr()->error('Something went wrong');
            return response()->json(['success' => false], 400);
        }
    }
}
