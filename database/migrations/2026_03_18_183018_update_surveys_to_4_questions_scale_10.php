<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Borrar encuestas anteriores (las preguntas y escala cambiaron por completo)
        DB::table('surveys')->truncate();

        Schema::table('surveys', function (Blueprint $table) {
            // Eliminar q5 (ahora son solo 4 preguntas)
            $table->dropColumn('q5');
        });

        // Cambiar q1-q4 de tinyInteger (1-5) a tinyInteger (0-10)
        // No necesitamos cambiar el tipo porque tinyInteger ya soporta 0-255,
        // la validación se hace en el controlador.
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->tinyInteger('q5')->nullable()->after('q4');
        });
    }
};
