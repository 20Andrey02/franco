<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/ReportController.php
|--------------------------------------------------------------------------
| Controlador del dashboard de reportes y estadísticas del evento.
| Solo accesible para administradores (middleware 'role:admin' en web.php).
|
| Este es uno de los controllers más "pesados" porque calcula MUCHAS
| estadísticas en una sola función. Todas se pasan a la vista
| reports/index.blade.php donde se muestran con gráficas (Chart.js).
|
| ESTADÍSTICAS QUE GENERA:
|   - Ranking de estands por número de visitas
|   - Total de participantes y visitas
|   - Distribución por sexo (M/F/O)
|   - Participantes activos (con al menos 1 visita)
|   - Visitas por hora del día (para ver las horas pico)
|   - Top 10 ciudades de origen
|   - Top 5 visitantes más activos
|   - Mapa de seguimiento con todas las visitas (paginado)
|
| NOTA: Si el evento tiene muchos datos, este controller puede tardar.
|       Todas las consultas usan Eloquent con SQL agrupado (groupBy).
| NOTA: La paginación del mapa de seguimiento usa 'visits_page' como parámetro
|       para no chocar con la paginación de otras secciones en la misma página.
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use App\Models\Stand;
use App\Models\Visit;
use App\Models\Participant;
use Illuminate\Support\Facades\DB; // Para usar DB::raw() en consultas con funciones SQL

class ReportController extends Controller
{
    /**
     * Genera todas las estadísticas y renderiza el dashboard de reportes.
     * Es un método largo pero cada sección está separada con comentarios.
     */
    public function index()
    {
        // ── 1. Ranking de estands por número de visitas ──
        // withCount('visits') agrega 'visits_count', luego ordenamos de mayor a menor
        $stands = Stand::withCount('visits')
            ->orderByDesc('visits_count')
            ->get();

        // ── 2. Estadísticas globales (números totales) ──
        $totalParticipants = Participant::count(); // Total de participantes registrados
        $totalVisits = Visit::count();             // Total de visitas registradas

        // ── 3. Distribución por sexo ──
        // Agrupa participantes por sexo y cuenta cada grupo
        // pluck('total', 'sexo') → convierte a array asociativo: ['M' => 10, 'F' => 12, 'O' => 1]
        // DB::raw('count(*) as total') → usa SQL puro para contar
        $bySex = Participant::select('sexo', DB::raw('count(*) as total'))
            ->groupBy('sexo')
            ->pluck('total', 'sexo');

        // ── 4. Participantes activos (que visitaron al menos un estand) ──
        // distinct() evita contar al mismo participante dos veces
        $activeParticipants = Visit::distinct('participant_id')->count('participant_id');

        // ── 5. Visitas por hora del día (para gráfica de barras) ──
        // HOUR(visit_time) extrae la hora (0-23) de cada visita
        // Resultado: ['10' => 5, '11' => 12, '12' => 20, ...] (hora → cantidad)
        $visitsByHour = Visit::select(DB::raw('HOUR(visit_time) as hora'), DB::raw('count(*) as total'))
            ->groupBy('hora')
            ->orderBy('hora')
            ->pluck('total', 'hora');

        // ── 6. Participantes por ciudad (top 10 para gráfica) ──
        // whereNotNull y where != '' → excluir registros sin ciudad
        $byCity = Participant::select('ciudad', DB::raw('count(*) as total'))
            ->whereNotNull('ciudad')
            ->where('ciudad', '!=', '')
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'ciudad');

        // ── 7. Participantes por ciudad (TODOS, sin límite) ──
        // Similar al anterior pero sin limit(), para la tabla completa
        $allCities = Participant::select('ciudad', DB::raw('count(*) as total'))
            ->whereNotNull('ciudad')
            ->where('ciudad', '!=', '')
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->pluck('total', 'ciudad');

        // ── 8. Top 5 participantes con más visitas ──
        $topVisitors = Participant::withCount('visits')
            ->orderByDesc('visits_count')
            ->limit(5)
            ->get();

        // ── 9. Todos los visitantes que tienen al menos 1 visita ──
        // having() filtra DESPUÉS del groupBy/conteo (no se puede usar where con conteos)
        $allVisitors = Participant::withCount('visits')
            ->having('visits_count', '>', 0)
            ->orderByDesc('visits_count')
            ->get();

        // ── 10. Mapa de seguimiento: visitas paginadas ──
        // with(['participant', 'stand']) → carga los datos del participante y estand de cada visita
        // paginate(20, ['*'], 'visits_page') → 20 por página, usa 'visits_page' como parámetro en la URL
        //   Ejemplo: /reports?visits_page=3
        $paginatedVisits = Visit::with(['participant', 'stand'])
            ->orderBy('visit_time', 'desc')
            ->paginate(20, ['*'], 'visits_page');

        // Pasar TODAS las variables a la vista reports/index.blade.php
        // compact() crea un array asociativo: ['stands' => $stands, 'totalParticipants' => $totalParticipants, ...]
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
            'allVisitors',
            'paginatedVisits'
        ));
    }
}
