<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\InventoryLog::with('product')->orderByDesc('logged_at')->get();
        return view('inventory_logs.index', compact('logs'));
    }
    
}
