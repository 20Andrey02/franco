<?php
/*
|--------------------------------------------------------------------------
| Migración: add_comentarios_to_surveys_table (DUPLICADA)
|--------------------------------------------------------------------------
| Esta migración hace LO MISMO que 2026_03_10_000002_add_comentarios_to_surveys_table.
| Existe como respaldo por si la primera no se ejecutó.
| Ambas son seguras porque verifican con hasColumn() antes de agregar.
|
| NOTA: No pasa nada si ambas se ejecutan — la segunda simplemente no hace nada
|   porque ya existe la columna 'comentarios'.
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si la columna ya existe (por si la otra migración ya la creó)
        if (!Schema::hasColumn('surveys', 'comentarios')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->text('comentarios')->nullable()->after('q5');
            });
        }
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn('comentarios');
        });
    }
};
