<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/SurveyController.php
|--------------------------------------------------------------------------
| Controlador de encuestas de satisfacción del evento.
| Tiene dos "mundos":
|
| PÚBLICO (cualquier participante):
|   - show()  → Muestra la encuesta (5 preguntas escala 1-5 + comentarios)
|   - store() → Guarda las respuestas (solo una por participante)
|
| ADMIN (solo administradores):
|   - reports()     → Dashboard con promedios, gráficas y comentarios
|   - exportExcel() → Descarga todas las encuestas en archivo Excel (.xlsx)
|   - exportPdf()   → Descarga un resumen en PDF
|
| PREGUNTAS DE LA ENCUESTA (escala 1-5, tipo Likert):
|   q1: ¿Qué tal fue tu experiencia en el evento?
|   q2: ¿Disfrutaste de la comida y bebidas?
|   q3: ¿Los stands estaban bien organizados?
|   q4: ¿Recomendarías este evento a otros?
|   q5: ¿Volverías a un evento similar?
|
| FLUJO PARA EL PARTICIPANTE:
|   1. Después de visitar estands, le aparece un botón/link para llenar encuesta
|   2. La URL incluye su código QR: /survey?code=FRANCO-000042
|   3. Solo puede llenarla UNA vez (se verifica con Survey::where participant_id)
|   4. Si ya la llenó, lo redirige al dashboard con mensaje
|
| DEPENDENCIAS EXTERNAS:
|   - maatwebsite/excel → para exportar a Excel (SurveysExport.php)
|   - barryvdh/dompdf → para generar PDF
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Participant;
use App\Exports\SurveysExport;                  // Clase de exportación Excel (app/Exports/)
use Maatwebsite\Excel\Facades\Excel;            // Librería para exportar Excel
use Barryvdh\DomPDF\Facade\Pdf;                // Librería para generar PDF

class SurveyController extends Controller
{
    /**
     * Muestra el formulario de encuesta para un participante.
     * Recibe el código QR del participante como parámetro en la URL: ?code=FRANCO-XXXXXX
     *
     * Validaciones antes de mostrar la encuesta:
     *   1. Que venga el parámetro 'code'
     *   2. Que el código QR exista en la tabla participants
     *   3. Que NO haya llenado ya la encuesta (solo una por persona)
     */
    public function show(Request $request)
    {
        $code = $request->query('code'); // Obtener ?code=XXX de la URL

        // Si no viene el código, redirigir al home con error
        if (!$code) {
            return redirect()->route('home')->with('error', 'Código de participante no proporcionado.');
        }

        // Buscar al participante por su código QR
        $participant = Participant::where('qr_code', $code)->first();

        if (!$participant) {
            return redirect()->route('home')->with('error', 'Participante no encontrado.');
        }

        // Verificar si ya llenó la encuesta (solo se permite una por participante)
        $existingSurvey = Survey::where('participant_id', $participant->id)->first();
        if ($existingSurvey) {
            // Si ya la llenó, redirigir a su dashboard con mensaje informativo
            return redirect()->route('visitors.dashboard', ['code' => $participant->qr_code])
                ->with('info', 'Ya has completado la encuesta. ¡Gracias!');
        }

        // Mostrar el formulario de encuesta (surveys/show.blade.php)
        return view('surveys.show', compact('participant'));
    }

    /**
     * Guarda las respuestas de la encuesta.
     * Recibe los datos del formulario via POST.
     *
     * Campos: participant_id, q1-q5 (enteros 1-5), comentarios (texto opcional)
     */
    public function store(Request $request)
    {
        $participant_id = $request->input('participant_id');

        // Validar todos los campos del formulario
        $data = $request->validate([
            'participant_id' => 'required|exists:participants,id', // Debe existir en la tabla participants
            'q1' => 'required|integer|between:1,5',                // Cada pregunta: número entero del 1 al 5
            'q2' => 'required|integer|between:1,5',
            'q3' => 'required|integer|between:1,5',
            'q4' => 'required|integer|between:1,5',
            'q5' => 'required|integer|between:1,5',
            'comentarios' => 'nullable|string|max:500',            // Comentarios opcionales (máx 500 caracteres)
        ]);

        // Doble verificación: si ya existe una encuesta para este participante, rechazar
        // (previene envíos duplicados, por ejemplo si dan doble click al botón)
        if (Survey::where('participant_id', $participant_id)->exists()) {
            return back()->with('error', 'Esta encuesta ya fue registrada.');
        }

        // Crear la encuesta en la base de datos
        Survey::create($data);

        // Redirigir al dashboard del participante con mensaje de agradecimiento
        $participant = Participant::findOrFail($participant_id);
        return redirect()->route('visitors.dashboard', ['code' => $participant->qr_code])
            ->with('success', '¡Gracias por llenar la encuesta!');
    }

    /**
     * Muestra el dashboard de reportes de encuestas (solo admin).
     * Calcula promedios generales y lista todas las encuestas + comentarios.
     */
    public function reports()
    {
        $totalSurveys = Survey::count();             // Total de encuestas completadas
        $totalParticipants = Participant::count();   // Total de participantes registrados

        // Calcular promedio de cada pregunta (1.0 a 5.0)
        // avg() → función de SQL que calcula el promedio automáticamente
        $averages = [
            'q1' => Survey::avg('q1'), // Promedio de la pregunta 1
            'q2' => Survey::avg('q2'),
            'q3' => Survey::avg('q3'),
            'q4' => Survey::avg('q4'),
            'q5' => Survey::avg('q5'),
        ];

        // Lista de todas las encuestas con datos del participante (paginada, 10 por página)
        // with('participant') → Eager Loading del participante asociado
        $surveys = Survey::with('participant')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page');

        // Lista de encuestas que SÍ tienen comentarios (paginada aparte)
        // Usa 'comments_page' como parámetro para no chocar con la paginación de arriba
        $comments = Survey::with('participant')
            ->whereNotNull('comentarios')
            ->where('comentarios', '!=', '')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'comments_page');

        // Texto de cada pregunta para mostrar en la vista
        $questions = [
            'q1' => '¿Qué tal fue tu experiencia en el evento?',
            'q2' => '¿Disfrutaste de la comida y bebidas?',
            'q3' => '¿Los stands estaban bien organizados?',
            'q4' => '¿Recomendarías este evento a otros?',
            'q5' => '¿Volverías a un evento similar?',
        ];

        return view('surveys.reports', compact('totalSurveys', 'totalParticipants', 'averages', 'surveys', 'comments', 'questions'));
    }

    /**
     * Exporta todas las encuestas a un archivo Excel (.xlsx)
     * Usa la clase SurveysExport (app/Exports/SurveysExport.php)
     * El archivo se descarga automáticamente con nombre: reportes_encuestas_FECHA.xlsx
     */
    public function exportExcel()
    {
        $timestamp = now()->format('Y-m-d_H-i-s'); // Formato: 2026-03-20_14-30-00
        return Excel::download(new SurveysExport, "reportes_encuestas_{$timestamp}.xlsx");
    }

    /**
     * Exporta un resumen de encuestas a PDF.
     * Genera un PDF con promedios, lista de encuestas y estadísticas.
     * Similar a reports() pero renderiza la vista surveys/pdf.blade.php
     * y la descarga como PDF.
     */
    public function exportPdf()
    {
        $totalSurveys = Survey::count();
        $totalParticipants = Participant::count();

        $averages = [
            'q1' => Survey::avg('q1'),
            'q2' => Survey::avg('q2'),
            'q3' => Survey::avg('q3'),
            'q4' => Survey::avg('q4'),
            'q5' => Survey::avg('q5'),
        ];

        // Para el PDF traemos TODAS las encuestas (sin paginar)
        // porque el PDF va completo
        $surveys = Survey::with('participant')
            ->orderBy('created_at', 'desc')
            ->get();

        // Texto corto de las preguntas (para el encabezado del PDF)
        $questions = [
            'q1' => 'Experiencia general',
            'q2' => 'Comida y bebidas',
            'q3' => 'Organización',
            'q4' => 'Recomendación',
            'q5' => 'Repetiría',
        ];

        // Generar PDF a partir de la vista Blade surveys/pdf.blade.php
        $pdf = Pdf::loadView('surveys.pdf', compact('totalSurveys', 'totalParticipants', 'averages', 'surveys', 'questions'));

        return $pdf->download('reportes_encuestas_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
