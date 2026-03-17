<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/Controller.php
|--------------------------------------------------------------------------
| Este es el Controller BASE del que heredan todos los demás controllers.
| No se le agrega lógica aquí — solo sirve como padre para que los demás
| controllers tengan acceso a los traits de Laravel:
|   - AuthorizesRequests: permite usar $this->authorize() para verificar permisos
|   - ValidatesRequests: permite usar $this->validate() para validar datos
|
| NOTA: Todos nuestros controllers (AuthController, ParticipantController, etc.)
|       extienden esta clase con "extends Controller"
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // Estos "traits" son como funciones extra que se le agregan a la clase
    // AuthorizesRequests → para autorización (verificar si el usuario tiene permiso)
    // ValidatesRequests  → para validar datos de formularios
    use AuthorizesRequests, ValidatesRequests;
}
