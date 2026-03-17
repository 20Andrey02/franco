<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Kernel.php
|--------------------------------------------------------------------------
| El HTTP Kernel es el "corazón" de cómo Laravel procesa cada petición.
| Aquí se definen TODOS los middleware que se ejecutan en la aplicación.
|
| HAY 3 TIPOS DE MIDDLEWARE:
|
| 1. $middleware (GLOBAL) → se ejecutan en TODAS las peticiones
|    - TrustProxies: confiar en proxies reversos (Cloudflare, nginx, etc.)
|    - HandleCors: manejar headers CORS (para peticiones de otros dominios)
|    - PreventRequestsDuringMaintenance: bloquear acceso en modo mantenimiento
|    - ValidatePostSize: verificar que el tamaño del POST no exceda el límite
|    - TrimStrings: quitar espacios al inicio/final de los strings
|    - ConvertEmptyStringsToNull: convertir strings vacíos a null
|
| 2. $middlewareGroups (GRUPOS) → se asignan por grupo a las rutas
|    - 'web': para rutas web normales (sesiones, cookies, CSRF)
|    - 'api': para rutas API (sin sesiones, con throttle/rate limit)
|
| 3. $middlewareAliases (ALIAS) → se asignan individualmente a rutas
|    - 'auth': verificar que esté logueado
|    - 'role': nuestro middleware personalizado para verificar roles
|    - 'guest': solo para usuarios NO logueados
|    - etc.
|
| NO OLVIDAR: Nuestro middleware personalizado 'role' está registrado
|   en la sección $middlewareAliases apuntando a CheckRole::class
|--------------------------------------------------------------------------
*/

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * MIDDLEWARE GLOBAL: se ejecutan en CADA petición a la aplicación.
     * Es como un "filtro" por el que pasa todo antes de llegar a las rutas.
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,          // Desactivado: para restringir hosts confiables
        \App\Http\Middleware\TrustProxies::class ,          // Confiar en proxies (necesario detrás de Cloudflare/nginx)
        \Illuminate\Http\Middleware\HandleCors::class ,     // Manejar CORS (Cross-Origin Resource Sharing)
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class , // Bloquear en modo mantenimiento (php artisan down)
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class , // Verificar tamaño de POST
        \App\Http\Middleware\TrimStrings::class ,           // Quitar espacios extra de los strings
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class , // "" → null (para que la BD almacene null en vez de "")
    ];

    /**
     * GRUPOS DE MIDDLEWARE: se aplican a grupos de rutas.
     * 'web' → para routes/web.php (tiene sesiones, cookies, CSRF)
     * 'api' → para routes/api.php (sin sesiones, con rate limiting)
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class ,                     // Encriptar cookies (seguridad)
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class , // Agregar cookies pendientes a la respuesta
            \Illuminate\Session\Middleware\StartSession::class ,             // Iniciar sesión PHP (necesario para auth)
            \Illuminate\View\Middleware\ShareErrorsFromSession::class ,     // Compartir errores con las vistas Blade ($errors)
            \App\Http\Middleware\VerifyCsrfToken::class ,                   // Verificar token CSRF (previene ataques CSRF)
            \Illuminate\Routing\Middleware\SubstituteBindings::class ,      // Inyectar modelos en rutas (Route Model Binding)
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Desactivado: para SPA con Sanctum
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api', // Rate limiting: 60 req/min (configurado en RouteServiceProvider)
            \Illuminate\Routing\Middleware\SubstituteBindings::class ,
        ],
    ];

    /**
     * ALIAS DE MIDDLEWARE: nombres cortos para usar en las rutas.
     * Ejemplo: Route::middleware('auth') usa la clase Authenticate.
     * Ejemplo: Route::middleware('role:admin') usa nuestra clase CheckRole.
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class ,                  // Verificar autenticación
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class , // Auth HTTP Basic (usuario/pass en header)
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class ,  // Invalidar sesiones en otros dispositivos
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class ,        // Configurar headers de caché HTTP
        'can' => \Illuminate\Auth\Middleware\Authorize::class ,                         // Verificar permisos (Policies)
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class ,                // Solo para usuarios NO logueados (redirige si está logueado)
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class ,     // Requiere confirmar contraseña
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class , // Para validación precognitiva (feature de Laravel 10+)
        'signed' => \App\Http\Middleware\ValidateSignature::class ,                    // Validar URLs firmadas (signed URLs)
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class ,         // Rate limiting genérico
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class ,       // Solo usuarios con email verificado

        // ★ NUESTRO MIDDLEWARE PERSONALIZADO ★
        // Uso: middleware('role:admin') o middleware('role:admin,scanner')
        'role' => \App\Http\Middleware\CheckRole::class ,
    ];
}
