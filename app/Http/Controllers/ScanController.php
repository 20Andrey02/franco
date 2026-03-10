<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        $stands = Stand::orderBy('nombre', 'asc')->get();
        return view('scan.index', compact('stands'));
    }
}
