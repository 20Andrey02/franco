<?php
/*
|--------------------------------------------------------------------------
| Migración: add_role_to_users_table
|--------------------------------------------------------------------------
| Agrega la columna 'role' a la tabla 'users'.
| Esta es una migración de ALTERACIÓN (modifica tabla existente, no crea nueva).
|
| VALORES POSIBLES DEL ROL:
|   - 'admin'   → Administrador (acceso total)
|   - 'scanner' → Escáner de QR (solo /scan)
|   - 'user'    → Visitante/participante (solo su dashboard)
|
| DEFAULT: 'admin' (por si se crea un usuario sin especificar rol)
| after('password') → la columna se inserta después de 'password' en la tabla
|
| NO OLVIDAR: Si cambias los roles aquí, también actualizar:
|   - app/Http/Middleware/CheckRole.php
|   - app/Http/Controllers/AuthController.php (redirectByRole)
|   - resources/views/layouts/app.blade.php (menú del sidebar)
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar columna 'role' con valor por defecto 'admin'
            // after('password') = ponerla justo después de la columna password
            $table->string('role')->default('admin')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role'); // Eliminar la columna al revertir
        });
    }
};
