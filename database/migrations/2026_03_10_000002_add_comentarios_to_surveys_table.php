<?php
/*
|--------------------------------------------------------------------------
| Migración: add_comentarios_to_surveys_table
|--------------------------------------------------------------------------
| Agrega el campo 'comentarios' a la tabla 'surveys'.
| Este campo no existió en la migración original y se agregó después.
|
| Usa hasColumn() para verificar si la columna ya existe antes de agregarla.
| Esto es una buena práctica para migraciones "seguras" que no fallan
| si se ejecutan dos veces.
|
| 'comentarios': texto opcional donde el participante puede escribir
|   sugerencias o comentarios sobre el evento (máx 500 chars en la validación).
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Verificar que la columna NO exista antes de agregarla
            // Esto evita error si la migración se ejecuta más de una vez
            if (!Schema::hasColumn('surveys', 'comentarios')) {
                $table->text('comentarios')->nullable()->after('q5'); // Texto largo, opcional, después de q5
            }
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            if (Schema::hasColumn('surveys', 'comentarios')) {
                $table->dropColumn('comentarios');
            }
        });
    }
};
