<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Middleware/Authenticate.php
|--------------------------------------------------------------------------
| Middleware de autenticación (viene incluido en Laravel).
| Se usa con: Route::middleware('auth') — aunque en nuestro proyecto
| usamos 'role:admin' que ya incluye verificación de login.
|
| Lo único que personalizamos aquí es la función redirectTo():
| define a dónde ir si el usuario NO está logueado.
|   - Si es petición JSON (API) → retorna null (error 401)
|   - Si es petición web → redirige a la ruta 'login' (/login)
|--------------------------------------------------------------------------
*/

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Define a dónde redirigir cuando el usuario no está autenticado.
     * Si es request JSON (API), retorna null para que Laravel mande 401.
     * Si es request web normal, manda al formulario de login.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
