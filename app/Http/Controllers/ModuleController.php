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
        $history = ModuleHistory::orderBy('created_at', 'desc')->take(100)->get();

        return view('modules.index', compact('modules', 'history'));
    }

    public function history($id)
    {
        $histories = ModuleHistory::Where('module_id', $id)->get();
        return view('modules.history', compact('histories'));
    }
}
