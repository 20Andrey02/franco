<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/HomeController.php
|--------------------------------------------------------------------------
| Controlador de la página principal (landing page).
| Es el más sencillo de todos — solo obtiene la lista de estands
| y la pasa a la vista home.blade.php para mostrarla.
|
| La ruta que usa: GET / → home (ver routes/web.php)
|
| NOTA: Este controller no usa autenticación, es 100% público.
| Si se quisiera agregar contenido dinámico a la landing page
| (como estadísticas en tiempo real), iría en el método index().
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Muestra la página principal del evento.
     * Obtiene todos los estands de la base de datos y los envía a la vista.
     * compact('stands') es equivalente a ['stands' => $stands]
     */
    public function index()
    {
        $stands = \App\Models\Stand::all(); // Obtiene todos los registros de la tabla 'stands'
        return view('home', compact('stands')); // Renderiza resources/views/home.blade.php
    }
}
