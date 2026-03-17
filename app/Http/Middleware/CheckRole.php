<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Middleware/CheckRole.php
|--------------------------------------------------------------------------
| Middleware PERSONALIZADO para verificar el rol del usuario.
| Este es el único middleware que creamos nosotros (los demás son de Laravel).
|
| ¿QUÉ ES UN MIDDLEWARE?
|   Es un "filtro" que se ejecuta ANTES de que la petición llegue al Controller.
|   Si el middleware dice "no pases", la petición se rechaza.
|
| USO EN RUTAS (ver routes/web.php):
|   Route::middleware('role:admin')         → Solo admin puede acceder
|   Route::middleware('role:admin,scanner') → Admin y scanner pueden acceder
|
| FLUJO:
|   1. El usuario hace una petición a una ruta protegida
|   2. Laravel ejecuta este middleware ANTES del controller
|   3. Verifica si hay sesión activa (auth()->check())
|   4. Compara el rol del usuario con los roles permitidos
|   5. Si el rol está permitido → deja pasar ($next)
|   6. Si no → redirige al home con mensaje de error
|
| REGISTRO DEL MIDDLEWARE:
|   Está registrado como 'role' en app/Http/Kernel.php:
|   'role' => \App\Http\Middleware\CheckRole::class
|
| NO OLVIDAR: Si agregas un nuevo rol (ej: 'coordinator'), no necesitas
|   tocar este middleware. Solo agrega el rol en las rutas:
|   Route::middleware('role:admin,coordinator')
|--------------------------------------------------------------------------
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verifica que el usuario tenga uno de los roles permitidos.
     *
     * @param Request $request  La petición HTTP entrante
     * @param Closure $next     La siguiente capa de middleware (o el controller)
     * @param string  ...$roles Los roles permitidos (se pasan después de los dos puntos en la ruta)
     *                          Ejemplo: middleware('role:admin,scanner') → $roles = ['admin', 'scanner']
     */
    public function handle(Request $request, Closure $next, string...$roles): Response
    {
        // Verificar que el usuario esté logueado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        // Obtener el rol del usuario actual
        $userRole = auth()->user()->role;

        // Verificar si el rol del usuario está en la lista de roles permitidos
        // in_array() busca $userRole dentro del array $roles
        if (!in_array($userRole, $roles)) {
            // Si no tiene permiso, redirigir al home con mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esa sección.');
        }

        // Si todo está bien, dejar pasar la petición al siguiente middleware o al controller
        return $next($request);
    }
}
