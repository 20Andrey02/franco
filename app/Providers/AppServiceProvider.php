<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Providers/AppServiceProvider.php
|--------------------------------------------------------------------------
| Service Provider principal de la aplicación.
| Aquí se configuran cosas que se necesitan al ARRANCAR la app.
|
| En nuestro caso, solo configuramos la paginación para que use
| Bootstrap 5 en lugar de Tailwind (que es el default de Laravel).
|
| ¿QUÉ ES UN SERVICE PROVIDER?
|   Es una clase que Laravel ejecuta al iniciar. Todos los providers
|   están registrados en config/app.php bajo el array 'providers'.
|   Tienen dos métodos:
|   - register() → Para registrar bindings en el contenedor de servicios
|   - boot()     → Para configuraciones que necesitan que todo ya esté cargado
|
| NO OLVIDAR: Si quitas Paginator::useBootstrapFive(), la paginación
|   volverá a usar clases de Tailwind y se verá rota con nuestro Bootstrap.
|--------------------------------------------------------------------------
*/

namespace App\Providers;

use Illuminate\Pagination\Paginator; // Clase que controla el estilo de la paginación
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios en el contenedor.
     * (Vacío en nuestro caso — no necesitamos registrar servicios custom)
     */
    public function register(): void
    {
        //
    }

    /**
     * Configuraciones al arrancar la aplicación.
     * Se ejecuta después de que todos los providers se han registrado.
     */
    public function boot(): void
    {
        // Decirle a Laravel que use Bootstrap 5 para los links de paginación
        // Sin esto, $participants->links() generaría HTML con clases de Tailwind CSS
        // y no se vería bien porque nosotros usamos Bootstrap 5
        Paginator::useBootstrapFive();
    }
}
