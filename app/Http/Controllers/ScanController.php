<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/ScanController.php
|--------------------------------------------------------------------------
| Controlador para la página del escáner QR.
| Este es el controller más sencillo — solo carga la lista de estands
| y muestra la vista del escáner (scan/index.blade.php).
|
| La lógica pesada del escaneo NO está aquí — está en ParticipantController@visit()
| que se llama por AJAX desde el JavaScript de la vista scan/index.blade.php.
|
| FLUJO:
| 1. El encargado del estand va a /scan (GET)
| 2. Selecciona su estand de un dropdown
| 3. Escanea el QR de un participante con la cámara
| 4. El JavaScript envía POST a /visit con code + stand_id
| 5. ParticipantController@visit() procesa y retorna JSON
| 6. La vista muestra el resultado (éxito o error de cooldown)
|
| Accesible para: admin y scanner (middleware 'role:admin,scanner' en web.php)
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use App\Models\Stand;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Muestra la página del escáner QR.
     * Carga todos los estands para el dropdown de selección.
     * La vista usa la librería html5-qrcode.min.js para acceder a la cámara.
     */
    public function index()
    {
        // Obtener todos los estands ordenados alfabéticamente para el selector
        $stands = Stand::orderBy('nombre', 'asc')->get();
        return view('scan.index', compact('stands'));
    }
}
