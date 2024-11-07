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
}
