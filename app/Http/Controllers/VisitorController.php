<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/VisitorController.php
|--------------------------------------------------------------------------
| Controlador del panel de visitantes (participantes del evento).
| Estas rutas son PÚBLICAS — los visitantes no necesitan loguearse con
| email/password, solo con su código QR.
|
| PÁGINAS:
|   - /visitors              → Página donde ingresan su código QR (o lo escanean)
|   - /visitors/dashboard    → Dashboard personal del visitante
|
| DASHBOARD DEL VISITANTE MUESTRA:
|   - Datos personales del participante
|   - Lista de estands visitados (con marca de ✓ visitado)
|   - Historial de visitas con fecha/hora
|   - Estado de la encuesta (completada o pendiente)
|   - Total de visitas realizadas
|
| NO OLVIDAR: Las URLs del QR apuntan a /visit (para escaneo en estand),
|   no a /visitors/dashboard. El dashboard se accede desde otro flujo.
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Stand;
use App\Models\Survey;

class VisitorController extends Controller
{
    /**
     * Muestra la página de "login" para visitantes.
     * Aquí los participantes pueden ingresar o escanear su código QR.
     * También carga la lista de estands para mostrar en la página.
     */
    public function index()
    {
        $stands = Stand::all();
        return view('visitors.index', compact('stands'));
    }

    /**
     * Muestra el dashboard personalizado del visitante.
     * Recibe el código QR como parámetro: /visitors/dashboard?code=FRANCO-XXXXXX
     *
     * DATOS QUE CALCULA Y ENVÍA A LA VISTA:
     *   - $participant: datos del participante
     *   - $visits: historial de visitas (con info del estand, ordenadas por fecha desc)
     *   - $survey: encuesta del participante (null si no la ha llenado)
     *   - $stands: todos los estands disponibles
     *   - $standsInfo: colección con info de cada estand + si fue visitado
     *   - $totalVisits: total de visitas del participante
     */
    public function dashboard(Request $request)
    {
        $code = $request->query('code'); // Obtener ?code=FRANCO-XXXXXX de la URL

        // Si no viene el código, redirigir al índice de visitantes
        if (!$code) {
            return redirect()->route('visitors.index')->with('error', 'Código QR no proporcionado.');
        }

        // Buscar al participante por su código QR
        $participant = Participant::where('qr_code', $code)->first();

        if (!$participant) {
            return redirect()->route('visitors.index')->with('error', 'Código QR no encontrado.');
        }

        // Obtener TODAS las visitas del participante con los datos del estand
        // with('stand') → Eager Loading para no hacer una consulta por cada visita
        $visits = $participant->visits()
            ->with('stand')
            ->orderBy('visit_time', 'desc')
            ->get();

        // Verificar si ya completó la encuesta
        // Si $survey es null → aún no la ha llenado → se le muestra botón para llenarla
        $survey = Survey::where('participant_id', $participant->id)->first();

        // Obtener todos los estands y construir info con estado de visitado
        $stands = Stand::all();
        $standsInfo = $stands->map(function ($stand) use ($visits) {
            return [
                'id' => $stand->id,
                'nombre' => $stand->nombre,
                'platillo' => 'Especialidad local',        // Texto genérico para la tarjeta
                'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'encargado' => $stand->encargado,
                'visited' => $visits->where('stand_id', $stand->id)->count() > 0,  // true si ya lo visitó
                'last_visit' => $visits->where('stand_id', $stand->id)->first()?->visit_time,
                // El ?-> (nullsafe operator) evita error si first() retorna null
            ];
        });

        $totalVisits = $visits->count(); // Total de visitas de este participante

        return view('visitors.dashboard', compact('participant', 'visits', 'survey', 'stands', 'standsInfo', 'totalVisits'));
    }
}
