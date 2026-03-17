<?php
/*
|--------------------------------------------------------------------------
| Migración: create_stands_table
|--------------------------------------------------------------------------
| Crea la tabla 'stands' para los estands de comida francesa.
|
| COLUMNAS:
|   - nombre: nombre del estand (obligatorio, ej: "Crème Brûlée")
|   - platillo: tipo de platillo (opcional, ej: "Crema flameada")
|   - descripcion: texto descriptivo largo (opcional)
|   - encargado: nombre del estudiante responsable (opcional)
|
| Los 8 estands iniciales se crean con database/seeders/StandSeeder.php
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('stands', function (Blueprint $table) {
            $table->id();                                // ID autoincremental
            $table->string('nombre');                    // Nombre del estand (obligatorio)
            $table->string('platillo')->nullable();      // Tipo de platillo (opcional)
            $table->text('descripcion')->nullable();     // Descripción larga (text, no string)
            $table->string('encargado')->nullable();     // Nombre del encargado (opcional)
            $table->timestamps();                        // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stands');
    }
};
