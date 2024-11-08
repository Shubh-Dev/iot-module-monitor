<?php

namespace App\Http\Controllers;
use App\Models\Module;
use App\Models\ModuleHistory;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        $history = ModuleHistory::orderBy('created_at', 'desc')->take(10)->get();

        return view('modules.index', compact('modules', 'history'));
    }

    // public function history($id)
    // {
    //     $histories = ModuleHistory::Where('module_id', $id)->get();
    //     return view('modules.history', compact('histories'));
    // }

    public function history($id)
{
    $histories = ModuleHistory::where('module_id', $id)->orderBy('created_at', 'desc')->get();

    // Check if the request is an AJAX request
    if (request()->ajax()) {
        return response()->json($histories);
    }

    // Otherwise, load the normal view (if needed for other cases)
    return view('modules.history', compact('histories'));
}
}
