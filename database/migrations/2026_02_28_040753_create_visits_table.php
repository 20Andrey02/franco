<?php
/*
|--------------------------------------------------------------------------
| Migración: create_visits_table
|--------------------------------------------------------------------------
| Crea la tabla 'visits' — es la tabla PIVOTE que registra cada visita
| de un participante a un estand. Es la tabla más importante del sistema.
|
| COLUMNAS:
|   - participant_id: FK a participants (ON DELETE CASCADE)
|   - stand_id: FK a stands (ON DELETE CASCADE)
|   - visit_time: cuándo se realizó la visita (se llena con now())
|
| NO tiene timestamps (created_at/updated_at) porque usa visit_time.
|
| CASCADE: Si se borra un participante o estand, sus visitas se eliminan automáticamente.
|   Esto es importante para no dejar "registros huérfanos" en la BD.
|
| NOTA: Un participante PUEDE visitar el mismo estand más de una vez
|   (después del cooldown de 15 min). No hay restricción única.
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            // foreignId() crea una columna bigint unsigned + índice
            // constrained() crea automáticamente la FK apuntando a participants.id
            // onDelete('cascade') → si se borra el participante, se borran sus visitas
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->foreignId('stand_id')->constrained()->onDelete('cascade');
            // useCurrent() → el valor por defecto es NOW() en la BD
            $table->timestamp('visit_time')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
