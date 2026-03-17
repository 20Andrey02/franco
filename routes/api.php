<?php
/*
|--------------------------------------------------------------------------
| Archivo: routes/api.php
|--------------------------------------------------------------------------
| Aquí van las rutas de la API REST (si la tuviéramos).
| A diferencia de web.php, estas rutas:
|   - No tienen sesiones ni cookies (son "stateless")
|   - Todas las URLs empiezan con /api/ automáticamente
|   - Usan tokens (Sanctum) en lugar de login con formulario
|
| En este proyecto casi no usamos API porque todo es web,
| pero Laravel incluye esta ruta por defecto para obtener
| los datos del usuario autenticado via API.
|
| NOTA: Si en el futuro quisiéramos hacer una app móvil nativa
|       que se conecte al servidor, las rutas irían aquí.
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Esta ruta devuelve los datos del usuario autenticado por API
// Se accede con: GET /api/user + token de Sanctum en el header Authorization
// Middleware 'auth:sanctum' verifica que el token sea válido
// En nuestro proyecto no la usamos directamente, es la que viene por defecto de Laravel
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // Retorna el modelo User como JSON
});
