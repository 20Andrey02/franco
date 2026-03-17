<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/ParticipantController.php
|--------------------------------------------------------------------------
| Controlador principal de participantes. Maneja TODO lo relacionado con
| los asistentes al evento de la Francofonía:
|
| FUNCIONES CRUD (solo admin):
|   - index()   → Lista de participantes con conteo de visitas (paginada, 15 por página)
|   - create()  → Mostrar formulario de registro
|   - store()   → Guardar nuevo participante + crear usuario + generar código QR
|   - show()    → Ver detalle de un participante (QR, visitas, info)
|   - edit()    → Formulario de edición
|   - update()  → Guardar cambios
|   - destroy() → Eliminar participante
|
| FUNCIONES ESPECIALES:
|   - badge()     → Ver gafete HTML en pantalla
|   - badgePdf()  → Generar y descargar gafete en PDF (usa librería DomPDF)
|   - visit()     → ★ FUNCIÓN CORE ★ Registrar visita cuando escanean un QR en un estand
|
| FORMATO DEL QR:
|   El código QR se genera como: "FRANCO-" + ID con 6 dígitos (ej: FRANCO-000042)
|   La URL que contiene el QR es: http://IP_DEL_SERVIDOR/visit?code=FRANCO-XXXXXX
|
| COOLDOWN:
|   Para evitar que alguien escanee el mismo QR muchas veces en un estand,
|   hay un tiempo de espera (cooldown) de 15 minutos entre visitas al MISMO estand.
|   Puede visitar OTROS estands inmediatamente.
|
| NO OLVIDAR: Si cambias la IP del servidor, actualizar APP_URL en el archivo .env
|             para que los QR apunten a la dirección correcta.
| NO OLVIDAR: El campo del correo en la tabla participants se llama 'correo', NO 'email'
| NO OLVIDAR: El campo del apellido paterno se llama 'paterno', NO 'apellido'
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;    // Modelo de participantes (tabla 'participants')
use App\Models\User;           // Modelo de usuarios del sistema (tabla 'users')
use App\Models\Visit;          // Modelo de visitas (tabla 'visits')
use App\Models\Stand;          // Modelo de estands (tabla 'stands')
use App\Models\Survey;         // Modelo de encuestas (tabla 'surveys')
use SimpleSoftwareIO\QrCode\Facades\QrCode;  // Librería para generar QRs en SVG
use Barryvdh\DomPDF\Facade\Pdf;             // Librería para generar PDFs

class ParticipantController extends Controller
{
    // Constante: minutos que debe esperar un participante antes de volver al MISMO estand
    // Se puede cambiar aquí y afecta todo el sistema
    const COOLDOWN_MIN = 15; // minutos de espera antes de volver al mismo stand

    /**
     * Lista todos los participantes con su conteo de visitas.
     * withCount('visits') agrega el campo 'visits_count' con el número de visitas de cada uno.
     * paginate(15) divide los resultados en páginas de 15.
     */
    public function index()
    {
        // withCount('visits') → agrega automáticamente una columna virtual 'visits_count'
        //   que cuenta cuántos registros tiene cada participante en la tabla 'visits'
        // orderBy('id', 'desc') → muestra los más recientes primero
        // paginate(15) → 15 participantes por página (la paginación se muestra en la vista con $participants->links())
        $participants = Participant::withCount('visits')->orderBy('id', 'desc')->paginate(15);
        return view('participants.index', compact('participants'));
    }

    /**
     * Muestra el formulario de registro de nuevo participante.
     * Solo retorna la vista — no necesita datos del servidor.
     */
    public function create()
    {
        return view('participants.create');
    }

    /**
     * Guarda un nuevo participante en la base de datos.
     *
     * FLUJO:
     * 1. Valida los datos del formulario (nombre, paterno, correo, etc.)
     * 2. Crea el registro en la tabla 'participants'
     * 3. Genera el código QR: "FRANCO-" + ID con ceros a la izquierda
     * 4. Crea un User asociado para que el participante pueda loguearse
     *    - email = correo del participante
     *    - password = código QR (ej: FRANCO-000042) hasheado con bcrypt
     * 5. Redirige al detalle del participante con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validación de datos — si algún campo no cumple, Laravel regresa error automáticamente
        $data = $request->validate([
            'nombre' => 'required|string|max:100',                 // Nombre obligatorio
            'paterno' => 'required|string|max:100',                // Apellido paterno obligatorio
            'materno' => 'nullable|string|max:100',                // Apellido materno opcional
            'ciudad' => 'nullable|string|max:100',                 // Ciudad opcional
            'municipio' => 'nullable|string|max:100',              // Municipio opcional
            'sexo' => 'required|in:M,F,O',                         // Sexo: M=Masculino, F=Femenino, O=Otro
            'correo' => 'required|email|unique:participants,correo', // Email único en la tabla participants
        ]);

        // Crear el participante en la BD
        $participant = Participant::create($data);

        // Generar QR code: FRANCO-000001, FRANCO-000042, etc.
        // str_pad agrega ceros a la izquierda para que siempre tenga 6 dígitos
        $participant->qr_code = 'FRANCO-' . str_pad($participant->id, 6, '0', STR_PAD_LEFT);
        $participant->save();

        // Crear cuenta de usuario asociada para que pueda loguearse
        // Su contraseña es el código QR (así el participante puede entrar con su QR)
        // bcrypt() hashea la contraseña para almacenarla de forma segura
        User::create([
            'name'     => $data['nombre'] . ' ' . $data['paterno'],   // Nombre completo
            'email'    => $data['correo'],                             // Mismo correo que el participante
            'password' => bcrypt($participant->qr_code),               // Contraseña = código QR hasheado
            'role'     => 'user',                                       // Rol de usuario normal (visitante)
        ]);

        // Redirigir al detalle del participante recién creado
        return redirect()->route('participants.show', $participant)
            ->with('success', 'Participante registrado exitosamente.'); // Mensaje flash para la vista
    }

    /**
     * Muestra el detalle de un participante: su info, código QR, historial de visitas.
     *
     * @param string $id  ID del participante
     */
    public function show(string $id)
    {
        // findOrFail() busca por ID — si no existe, automáticamente muestra error 404
        // with('visits.stand') → carga las visitas Y el estand de cada visita (Eager Loading)
        //   Esto evita el problema "N+1 queries" (muchas consultas separadas a la BD)
        $participant = Participant::with('visits.stand')->findOrFail($id);

        // URL que contendrá el QR del gafete
        // Cuando se escanee, llevará a: /visit?code=FRANCO-XXXXXX
        $qrUrl = url("/visit?code={$participant->qr_code}");

        return view('participants.show', compact('participant', 'qrUrl'));
    }

    /**
     * Muestra el gafete del participante en formato HTML (para ver en pantalla).
     */
    public function badge(string $id)
    {
        $participant = Participant::findOrFail($id);
        $qrUrl = url("/visit?code={$participant->qr_code}");
        return view('participants.badge', compact('participant', 'qrUrl'));
    }

    /**
     * Genera el gafete del participante como PDF descargable.
     * Usa la librería DomPDF para convertir HTML a PDF.
     * El QR se genera como SVG inline usando la librería SimpleSoftwareIO/QrCode.
     *
     * NOTA: El tamaño del papel es personalizado [0, 0, 340, 500] puntos ≈ tarjeta pequeña
     */
    public function badgePdf(string $id)
    {
        $participant = Participant::findOrFail($id);
        $qrUrl = url("/visit?code={$participant->qr_code}");

        // Generar QR como SVG (imagen vectorial) con alta corrección de errores ('H')
        // size(180) → 180 pixeles de ancho/alto
        // errorCorrection('H') → corrección alta, el QR funciona aunque tenga hasta 30% dañado
        $qrSvg = QrCode::size(180)->errorCorrection('H')->generate($qrUrl);

        // Cargar la vista blade del gafete PDF y pasarle los datos
        $pdf = Pdf::loadView('participants.badge-pdf', compact('participant', 'qrUrl', 'qrSvg'));

        // Tamaño de papel personalizado en puntos (no es carta ni A4 — es como una tarjeta)
        $pdf->setPaper([0, 0, 340, 500]);

        // Descargar el PDF con nombre: gafete-FRANCO-000042.pdf
        $filename = 'gafete-' . $participant->qr_code . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Muestra el formulario de edición de un participante.
     */
    public function edit(string $id)
    {
        $participant = Participant::findOrFail($id);
        return view('participants.edit', compact('participant'));
    }

    /**
     * Actualiza los datos de un participante existente.
     * La validación de 'correo' incluye una excepción para el ID actual
     * (unique:participants,correo,{id}) para que no marque error por su propio correo.
     */
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
            // El ", $participant->id" al final le dice a Laravel: "ignora este ID al verificar unicidad"
        ]);
        $participant->update($data);
        return redirect()->route('participants.show', $participant)
            ->with('success', 'Participante actualizado.');
    }

    /**
     * Elimina un participante de la base de datos.
     * CUIDADO: También se eliminan sus visitas y encuesta por el CASCADE en la migración.
     */
    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();
        return redirect()->route('participants.index')
            ->with('success', 'Participante eliminado.');
    }

    /**
     * ★★★ FUNCIÓN PRINCIPAL DEL EVENTO ★★★
     *
     * Registra una visita cuando se escanea un código QR en un estand.
     * Se llama desde el escáner QR (scan/index.blade.php) via AJAX (fetch/POST).
     *
     * PARÁMETROS ESPERADOS:
     *   - code: código QR del participante (ej: "FRANCO-000042")
     *   - stand_id: ID del estand donde se escanea
     *
     * RESPUESTA: JSON con éxito/error + datos del participante
     *
     * REGLAS DE NEGOCIO:
     *   1. El código QR debe existir en la tabla participants
     *   2. El stand_id debe existir en la tabla stands
     *   3. Si el participante ya visitó ESE MISMO estand hace menos de 15 min → error de cooldown
     *   4. Si todo está bien → crea el registro de visita y retorna JSON con datos
     *
     * NOTA: Esta función responde siempre en JSON porque se llama desde JavaScript (AJAX),
     *       no desde un formulario HTML normal.
     */
    public function visit(Request $request)
    {
        // Intentar obtener 'code' del body (POST) o de la URL (GET ?code=...)
        $code = $request->input('code') ?? $request->query('code');
        $stand_id = $request->input('stand_id') ?? $request->query('stand');

        // Verificar que llegaron ambos parámetros
        if (!$code || !$stand_id) {
            return response()->json(['success' => false, 'message' => 'Faltan parámetros (code, stand_id).'], 400);
        }

        // Buscar al participante por su código QR
        $participant = Participant::where('qr_code', $code)->first();
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Código QR no encontrado.'], 404);
        }

        // Buscar el estand
        $stand = Stand::find($stand_id);
        if (!$stand) {
            return response()->json(['success' => false, 'message' => 'Estand no encontrado.'], 404);
        }

        // ── Verificar cooldown (tiempo de espera entre visitas al MISMO estand) ──
        // Buscamos la última visita de este participante a ESTE estand específico
        $lastVisit = Visit::where('participant_id', $participant->id)
            ->where('stand_id', $stand_id)
            ->orderByDesc('visit_time')
            ->first();

        if ($lastVisit) {
            // Calcular cuántos minutos pasaron desde la última visita
            $minutesAgo = now()->diffInMinutes($lastVisit->visit_time);
            if ($minutesAgo < self::COOLDOWN_MIN) {
                // Aún no pasan los 15 minutos — calcular cuánto falta
                $waitMin = self::COOLDOWN_MIN - $minutesAgo;
                return response()->json([
                    'success' => false,
                    'message' => "Este participante ya visitó este estand. Puede volver en {$waitMin} min.",
                ]);
            }
        }

        // ── Todo bien — registrar la visita ──
        Visit::create([
            'participant_id' => $participant->id,
            'stand_id' => $stand_id,
            'visit_time' => now(), // Fecha y hora actual del servidor
        ]);

        // Verificar si el participante ya completó la encuesta de satisfacción
        $surveyClosed = Survey::where('participant_id', $participant->id)->exists();

        // Contar el total de visitas de este participante (a cualquier estand)
        $totalVisits = Visit::where('participant_id', $participant->id)->count();

        // Respuesta JSON exitosa — el escáner mostrará estos datos en pantalla
        return response()->json([
            'success' => true,
            'message' => "✓ Visita registrada: {$participant->nombre} {$participant->paterno}",
            'participante' => $participant->nombre . ' ' . $participant->paterno,
            'visitas_totales' => $totalVisits,                  // Total de visitas a todos los estands
            'qr_code' => $participant->qr_code,                 // Código QR del participante
            'survey_completed' => $surveyClosed,                 // true/false si ya llenó encuesta
            'survey_url' => route('survey.show', ['code' => $participant->qr_code]), // URL para llenar encuesta
        ]);
    }
}
