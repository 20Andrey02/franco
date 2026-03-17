<?php
/*
|--------------------------------------------------------------------------
| Migración: add_details_to_stands_table
|--------------------------------------------------------------------------
| Agrega 'platillo' y 'descripcion' a la tabla 'stands'.
| Estos campos se agregaron después de la migración original.
|
| Usa hasColumn() para evitar errores si las columnas ya existen
| (migración idempotente/segura).
|
| NOTA: Actualmente la migración original create_stands_table ya incluye
|   estos campos. Esta migración existe por si alguien tenía la BD antes
|   de que se agregaran 'platillo' y 'descripcion'.
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stands', function (Blueprint $table) {
            // Solo agregar si no existe (migración segura)
            if (!Schema::hasColumn('stands', 'platillo')) {
                $table->string('platillo')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('stands', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('platillo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stands', function (Blueprint $table) {
            $table->dropColumn(['platillo', 'descripcion']);
        });
    }
};
