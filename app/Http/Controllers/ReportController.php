<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use App\Models\Visit;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Ranking de stands por número de visitas
        $stands = Stand::withCount('visits')
            ->orderByDesc('visits_count')
            ->get();

        // Estadísticas globales
        $totalParticipants = Participant::count();
        $totalVisits = Visit::count();

        // Participantes por sexo
        $bySex = Participant::select('sexo', DB::raw('count(*) as total'))
            ->groupBy('sexo')
            ->pluck('total', 'sexo');

        // Participantes con al menos una visita
        $activeParticipants = Visit::distinct('participant_id')->count('participant_id');

        return view('reports.index', compact(
            'stands',
            'totalParticipants',
            'totalVisits',
            'bySex',
            'activeParticipants'
        ));
    }
}
