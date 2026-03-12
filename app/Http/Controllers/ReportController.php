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

        // Visitas por hora del día (0-23)
        $visitsByHour = Visit::select(DB::raw('HOUR(visit_time) as hora'), DB::raw('count(*) as total'))
            ->groupBy('hora')
            ->orderBy('hora')
            ->pluck('total', 'hora');

        // Participantes por ciudad (top 10)
        $byCity = Participant::select('ciudad', DB::raw('count(*) as total'))
            ->whereNotNull('ciudad')
            ->where('ciudad', '!=', '')
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'ciudad');

        // Participantes por ciudad (TODOS)
        $allCities = Participant::select('ciudad', DB::raw('count(*) as total'))
            ->whereNotNull('ciudad')
            ->where('ciudad', '!=', '')
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->pluck('total', 'ciudad');

        // Top 5 participantes con más visitas
        $topVisitors = Participant::withCount('visits')
            ->orderByDesc('visits_count')
            ->limit(5)
            ->get();

        // Todos los visitantes con visitas
        $allVisitors = Participant::withCount('visits')
            ->having('visits_count', '>', 0)
            ->orderByDesc('visits_count')
            ->get();

        return view('reports.index', compact(
            'stands',
            'totalParticipants',
            'totalVisits',
            'bySex',
            'activeParticipants',
            'visitsByHour',
            'byCity',
            'allCities',
            'topVisitors',
            'allVisitors'
        ));
    }
}
