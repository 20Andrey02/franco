<?php
/*
|--------------------------------------------------------------------------
| Archivo: routes/web.php
|--------------------------------------------------------------------------
| Este archivo es el "mapa" de todas las URLs de nuestra aplicación web.
| Cada línea Route::get/post/etc. conecta una URL con un método
| de un Controller que se encarga de procesar la petición.
|
| CONCEPTOS CLAVE:
| - Route::get('url', [Controller, 'metodo'])  → cuando el usuario VISITA una URL
| - Route::post('url', [Controller, 'metodo']) → cuando el usuario ENVÍA un formulario
| - Route::resource(...)  → crea automáticamente 7 rutas CRUD (index, create, store, show, edit, update, destroy)
| - ->name('nombre')      → le da un nombre a la ruta para poder usarlo con route('nombre') en Blade
| - middleware('role:admin') → protege las rutas: solo usuarios con ese rol pueden acceder
|
| NOTA: Si agregas un nuevo Controller, no olvides importarlo arriba con "use App\Http\Controllers\NuevoController;"
| NOTA: El orden de las rutas importa — Laravel usa la PRIMERA que coincida
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;

// Importamos todos los controllers que vamos a usar en las rutas
// (cada controller es una clase PHP que tiene los métodos para manejar las peticiones)
use App\Http\Controllers\AuthController;          // Login, logout, redirección por rol
use App\Http\Controllers\HomeController;           // Página principal pública
use App\Http\Controllers\ParticipantController;    // CRUD de participantes + registro de visitas QR
use App\Http\Controllers\StandController;          // CRUD de estands de comida
use App\Http\Controllers\ReportController;         // Dashboard de reportes y estadísticas
use App\Http\Controllers\ScanController;           // Página del escáner QR para los estands
use App\Http\Controllers\SurveyController;         // Encuesta de satisfacción (mostrar, guardar, reportes, exportar)
use App\Http\Controllers\VisitorController;        // Panel del visitante (login con QR, dashboard personal)

// ══════════════════════════════════════════════════════════════
// ══ RUTAS PÚBLICAS (cualquier persona puede acceder) ═════════
// ══════════════════════════════════════════════════════════════

// Página de inicio — muestra la landing page con la lista de estands
// (No requiere login, es lo primero que ven todos los usuarios)
Route::get('/', [HomeController::class , 'index'])->name('home');

// ── Verificación de conectividad (Access Point sin internet) ─
// Cuando un celular se conecta a un WiFi, hace peticiones a URLs específicas
// para verificar si hay internet. Si no las responde, Android DESCONECTA el WiFi.
// Estas rutas "engañan" al celular haciéndole creer que hay internet.
// REQUISITO: El DNS del Access Point debe apuntar a la IP de este servidor.
// Android: espera un HTTP 204 en /generate_204
Route::get('/generate_204', fn () => response('', 204));
Route::get('/gen_204', fn () => response('', 204));
// Variante de Android con dominio Google connectivity
Route::get('/connectivitycheck/gstatic/generate_204', fn () => response('', 204));
// iOS: espera un HTTP 200 con "Success" en /hotspot-detect.html
Route::get('/hotspot-detect.html', fn () => response('<HTML><HEAD><TITLE>Success</TITLE></HEAD><BODY>Success</BODY></HTML>'));
// Windows: espera un HTTP 200 con "Microsoft NCSI" en /ncsi.txt
Route::get('/ncsi.txt', fn () => response('Microsoft NCSI'));

// ── Encuestas (Pública) ─────────────────────────────────────
// La encuesta es pública porque los visitantes acceden desde un link con su código QR
// Ejemplo de URL: /survey?code=FRANCO-000101
Route::get('/survey', [SurveyController::class, 'show'])->name('survey.show');     // Mostrar formulario de encuesta
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');   // Guardar respuestas de encuesta

// ── Panel de Visitantes (Pública) ────────────────────────────
// Los visitantes no usan email/password — se identifican con su código QR
// /visitors        → página donde ingresan su QR (o lo escanean con cámara)
// /visitors/dashboard?code=FRANCO-000101 → su dashboard personal con visitas y encuesta
Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
Route::get('/visitors/dashboard', [VisitorController::class, 'dashboard'])->name('visitors.dashboard');

// ══════════════════════════════════════════════════════════════
// ══ RUTAS DE AUTENTICACIÓN (login/logout) ════════════════════
// ══════════════════════════════════════════════════════════════

// GET  /login → muestra el formulario de login (auth/login.blade.php)
// POST /login → valida credenciales y redirige según el rol del usuario
// POST /logout → cierra la sesión y redirige al inicio
// NOTA: El nombre 'login' es especial en Laravel — el middleware 'auth' redirige aquí cuando no estás logueado
Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
Route::post('/login', [AuthController::class , 'login'])->name('login.post');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

// ══════════════════════════════════════════════════════════════
// ══ RUTAS DE ADMINISTRADOR (solo rol: admin) ═════════════════
// ══════════════════════════════════════════════════════════════
// middleware('role:admin') usa nuestro middleware personalizado CheckRole
// (ver archivo: app/Http/Middleware/CheckRole.php)
// Si un usuario sin rol admin intenta entrar, lo redirige al home con mensaje de error
Route::middleware('role:admin')->group(function () {

    // -- Gafetes de participantes --
    // Estas rutas van ANTES de Route::resource porque si no, Laravel confunde 'badge' con un ID de participante
    Route::get('participants/{participant}/badge', [ParticipantController::class, 'badge'])->name('participants.badge');         // Ver gafete HTML
    Route::get('participants/{participant}/badge-pdf', [ParticipantController::class, 'badgePdf'])->name('participants.badge.pdf'); // Descargar gafete PDF

    // -- CRUD completo de Participantes --
    // Route::resource genera estas 7 rutas automáticamente:
    //   GET    /participants           → index   (listar todos)
    //   GET    /participants/create    → create  (formulario de nuevo)
    //   POST   /participants           → store   (guardar nuevo)
    //   GET    /participants/{id}      → show    (ver detalle)
    //   GET    /participants/{id}/edit → edit    (formulario de editar)
    //   PUT    /participants/{id}      → update  (guardar cambios)
    //   DELETE /participants/{id}      → destroy (eliminar)
    Route::resource('participants', ParticipantController::class);

    // -- CRUD completo de Estands --
    // Mismas 7 rutas que arriba pero para los estands de comida francesa
    Route::resource('stands', StandController::class);

    // -- Reportes y estadísticas --
    Route::get('reports', [ReportController::class , 'index'])->name('reports.index');                          // Dashboard general de reportes
    Route::get('reports/surveys', [SurveyController::class, 'reports'])->name('surveys.reports');               // Reportes de encuestas
    Route::get('reports/surveys/export/excel', [SurveyController::class, 'exportExcel'])->name('surveys.export.excel'); // Descargar encuestas en Excel
    Route::get('reports/surveys/export/pdf', [SurveyController::class, 'exportPdf'])->name('surveys.export.pdf');       // Descargar encuestas en PDF
});

// ══════════════════════════════════════════════════════════════
// ══ RUTAS DE ESCÁNER (roles: admin Y scanner) ════════════════
// ══════════════════════════════════════════════════════════════
// Tanto el admin como el scanner (encargado de estand) pueden escanear QRs
// middleware('role:admin,scanner') permite ambos roles
Route::middleware('role:admin,scanner')->group(function () {
    // Registro de visita cuando se escanea un QR en un estand
    // Acepta GET (si alguien pone la URL directa) y POST (desde el AJAX del escáner)
    // Parámetros: code=FRANCO-XXXXXX, stand_id=N
    // Responde en JSON con el resultado de la visita
    Route::match (['get', 'post'], 'visit', [ParticipantController::class , 'visit'])->name('visit');

    // Página del escáner QR — interfaz con cámara para que el encargado escanee gafetes
    Route::get('scan', [ScanController::class , 'index'])->name('scan.index');
});
