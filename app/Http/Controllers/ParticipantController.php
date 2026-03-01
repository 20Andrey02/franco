<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Visit;
use App\Models\Stand;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParticipantController extends Controller
{
    const MAX_VISITS = 5; // visitas totales máximas por participante
    const COOLDOWN_MIN = 30; // minutos de espera antes de volver al mismo stand

    public function index()
    {
        $participants = Participant::withCount('visits')->orderBy('id', 'desc')->paginate(15);
        return view('participants.index', compact('participants'));
    }

    public function create()
    {
        return view('participants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'sexo' => 'required|in:M,F,O',
            'correo' => 'required|email|unique:participants,correo',
        ]);

        $participant = Participant::create($data);
        $participant->qr_code = 'FRANCO-' . str_pad($participant->id, 6, '0', STR_PAD_LEFT);
        $participant->save();

        return redirect()->route('participants.show', $participant)
            ->with('success', 'Participante registrado exitosamente.');
    }

    public function show(string $id)
    {
        $participant = Participant::with('visits.stand')->findOrFail($id);
        $qrUrl = url("/visit?code={$participant->qr_code}");
        return view('participants.show', compact('participant', 'qrUrl'));
    }

    public function edit(string $id)
    {
        $participant = Participant::findOrFail($id);
        return view('participants.edit', compact('participant'));
    }

    public function update(Request $request, string $id)
    {
        $participant = Participant::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'sexo' => 'required|in:M,F,O',
            'correo' => 'required|email|unique:participants,correo,' . $participant->id,
        ]);
        $participant->update($data);
        return redirect()->route('participants.show', $participant)
            ->with('success', 'Participante actualizado.');
    }

    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();
        return redirect()->route('participants.index')
            ->with('success', 'Participante eliminado.');
    }

    /**
     * Register a visit when QR is scanned at a stand.
     * Expects POST params: code (qr_code) + stand_id
     * Returns JSON.
     */
    public function visit(Request $request)
    {
        $code = $request->input('code') ?? $request->query('code');
        $stand_id = $request->input('stand_id') ?? $request->query('stand');

        if (!$code || !$stand_id) {
            return response()->json(['success' => false, 'message' => 'Faltan parámetros (code, stand_id).'], 400);
        }

        $participant = Participant::where('qr_code', $code)->first();
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Código QR no encontrado.'], 404);
        }

        $stand = Stand::find($stand_id);
        if (!$stand) {
            return response()->json(['success' => false, 'message' => 'Estand no encontrado.'], 404);
        }

        // Regla 1: máximo MAX_VISITS visitas totales
        $totalVisits = Visit::where('participant_id', $participant->id)->count();
        $remaining = self::MAX_VISITS - $totalVisits;

        if ($remaining <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Este participante ya alcanzó el límite de " . self::MAX_VISITS . " visitas.",
                'visitas_restantes' => 0,
            ]);
        }

        // Regla 2: cooldown por stand
        $lastVisit = Visit::where('participant_id', $participant->id)
            ->where('stand_id', $stand_id)
            ->orderByDesc('visit_time')
            ->first();

        if ($lastVisit) {
            $minutesAgo = now()->diffInMinutes($lastVisit->visit_time);
            if ($minutesAgo < self::COOLDOWN_MIN) {
                $waitMin = self::COOLDOWN_MIN - $minutesAgo;
                return response()->json([
                    'success' => false,
                    'message' => "Este participante ya visitó este estand. Puede volver en {$waitMin} min.",
                    'visitas_restantes' => $remaining,
                ]);
            }
        }

        Visit::create([
            'participant_id' => $participant->id,
            'stand_id' => $stand_id,
            'visit_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "✓ Visita registrada: {$participant->nombre} {$participant->paterno}",
            'participante' => $participant->nombre . ' ' . $participant->paterno,
            'visitas_restantes' => $remaining - 1,
        ]);
    }
}
