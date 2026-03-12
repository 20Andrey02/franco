<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Stand;
use App\Models\Survey;

class VisitorController extends Controller
{
    /**
     * Show visitor login page
     */
    public function index()
    {
        $stands = Stand::all();
        return view('visitors.index', compact('stands'));
    }

    /**
     * Show visitor dashboard after QR authentication
     */
    public function dashboard(Request $request)
    {
        $code = $request->query('code');
        
        if (!$code) {
            return redirect()->route('visitors.index')->with('error', 'Código QR no proporcionado.');
        }

        $participant = Participant::where('qr_code', $code)->first();
        
        if (!$participant) {
            return redirect()->route('visitors.index')->with('error', 'Código QR no encontrado.');
        }

        // Get participant's visits with stand info
        $visits = $participant->visits()
            ->with('stand')
            ->orderBy('visit_time', 'desc')
            ->get();

        // Check if survey exists
        $survey = Survey::where('participant_id', $participant->id)->first();

        // Get all stands info
        $stands = Stand::all();
        $standsInfo = $stands->map(function ($stand) use ($visits) {
            return [
                'id' => $stand->id,
                'nombre' => $stand->nombre,
                'platillo' => 'Especialidad local',
                'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'encargado' => $stand->encargado,
                'visited' => $visits->where('stand_id', $stand->id)->count() > 0,
                'last_visit' => $visits->where('stand_id', $stand->id)->first()?->visit_time,
            ];
        });

        $totalVisits = $visits->count();

        return view('visitors.dashboard', compact('participant', 'visits', 'survey', 'stands', 'standsInfo', 'totalVisits'));
    }
}
