<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stand;
use App\Models\Visit;
use App\Models\Participant;

class StandController extends Controller
{
    /**
     * Display a listing of stands with visit counts.
     */
    public function index()
    {
        $stands = Stand::withCount('visits')->orderBy('nombre')->get();
        return view('stands.index', compact('stands'));
    }

    /**
     * Show the form for creating a new stand.
     */
    public function create()
    {
        return view('stands.create');
    }

    /**
     * Store a newly created stand.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'platillo' => 'nullable|string|max:200',
            'descripcion' => 'nullable|string|max:500',
            'encargado' => 'nullable|string|max:200',
        ]);

        $stand = Stand::create($data);
        return redirect()->route('stands.show', $stand)->with('success', 'Estand registrado correctamente.');
    }

    /**
     * Display the stand detail + recent visitors + QR scanner form.
     */
    public function show(string $id)
    {
        $stand = Stand::findOrFail($id);
        $recentVisits = Visit::with('participant')
            ->where('stand_id', $id)
            ->orderByDesc('visit_time')
            ->limit(20)
            ->get();
        $totalVisits = Visit::where('stand_id', $id)->count();
        return view('stands.show', compact('stand', 'recentVisits', 'totalVisits'));
    }

    /**
     * Show the form for editing.
     */
    public function edit(string $id)
    {
        $stand = Stand::findOrFail($id);
        return view('stands.edit', compact('stand'));
    }

    /**
     * Update the specified stand.
     */
    public function update(Request $request, string $id)
    {
        $stand = Stand::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'platillo' => 'nullable|string|max:200',
            'descripcion' => 'nullable|string|max:500',
            'encargado' => 'nullable|string|max:200',
        ]);
        $stand->update($data);
        return redirect()->route('stands.show', $stand)->with('success', 'Estand actualizado.');
    }

    /**
     * Remove the specified stand.
     */
    public function destroy(string $id)
    {
        $stand = Stand::findOrFail($id);
        $stand->delete();
        return redirect()->route('stands.index')->with('success', 'Estand eliminado.');
    }
}
