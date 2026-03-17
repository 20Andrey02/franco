<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Providers/RouteServiceProvider.php
|--------------------------------------------------------------------------
| Provider que configura cómo se cargan las rutas de la aplicación.
|
| FUNCIONES PRINCIPALES:
|   1. Define la constante HOME («cómo» redirigir después del login por defecto)
|   2. Configura el Rate Limiter para la API (60 peticiones por minuto)
|   3. Carga routes/api.php con prefijo '/api' y middleware 'api'
|   4. Carga routes/web.php con middleware 'web'
|
| NO OLVIDAR: La constante HOME = '/home' NO se usa en nuestro proyecto
|   porque nosotros redirigimos manualmente según el rol en AuthController.
|   Pero Laravel la usa internamente en algunos middlewares (ej: RedirectIfAuthenticated).
|--------------------------------------------------------------------------
*/

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Ruta a la que se redirige después del login.
     * En nuestro proyecto no se usa directamente porque AuthController
     * tiene su propia lógica de redirección por rol (redirectByRole).
     */
    public const HOME = '/home';

    /**
     * Configura las rutas y el rate limiter.
     */
    public function boot(): void
    {
        // Rate Limiter: limita la API a 60 peticiones por minuto por usuario (o por IP si no está logueado)
        // Esto protege contra abuso de la API (ej: alguien haciendo miles de peticiones)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Cargar los archivos de rutas
        $this->routes(function () {
            // Rutas API: prefijo /api, middleware 'api' (sin sesión ni CSRF)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rutas Web: middleware 'web' (con sesión, cookies, CSRF)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
