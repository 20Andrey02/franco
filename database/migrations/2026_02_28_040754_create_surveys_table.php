<?php
/*
|--------------------------------------------------------------------------
| Migración: create_surveys_table
|--------------------------------------------------------------------------
| Crea la tabla 'surveys' para las encuestas de satisfacción.
| Cada participante llena UNA sola encuesta con 5 preguntas (q1-q5).
|
| COLUMNAS:
|   - participant_id: FK a participants (CASCADE)
|   - q1 a q5: respuestas tipo Likert (tinyInteger: valores 1-5, nullable)
|   - timestamps: created_at (cuándo la llenó)
|
| NOTA: El campo 'comentarios' se agrega en una migración posterior
|   (add_comentarios_to_surveys_table). No está aquí.
|
| tinyInteger = número pequeño (0-255), ideal para escala 1-5.
|   Usa menos espacio que integer o bigInteger.
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('q1')->nullable();  // Pregunta 1: Experiencia general (1-5)
            $table->tinyInteger('q2')->nullable();  // Pregunta 2: Comida y bebidas (1-5)
            $table->tinyInteger('q3')->nullable();  // Pregunta 3: Organización (1-5)
            $table->tinyInteger('q4')->nullable();  // Pregunta 4: Recomendación (1-5)
            $table->tinyInteger('q5')->nullable();  // Pregunta 5: Repetiría (1-5)
            $table->timestamps();                   // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
