<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Models/Visit.php
|--------------------------------------------------------------------------
| Modelo Eloquent para la tabla 'visits'.
| Representa una visita de un participante a un estand.
| Es la tabla PIVOTE que conecta participantes con estands.
|
| TABLA EN LA BD: visits
| CAMPOS:
|   - id              → ID autoincremental
|   - participant_id  → FK → tabla 'participants' (ON DELETE CASCADE)
|   - stand_id        → FK → tabla 'stands' (ON DELETE CASCADE)
|   - visit_time      → Timestamp de cuándo se realizó la visita
|
| NOTA: Esta tabla NO tiene timestamps (created_at, updated_at).
|       En su lugar usa 'visit_time' con un timestamp personalizado.
|       Por eso $timestamps = false.
|
| RELACIONES:
|   - Una visita PERTENECE A un participante (belongsTo Participant)
|   - Una visita PERTENECE A un estand (belongsTo Stand)
|
| CASCADE: Si se elimina un participante o estand, sus visitas se borran automáticamente.
|--------------------------------------------------------------------------
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    // Desactivar timestamps automáticos (created_at, updated_at)
    // porque esta tabla usa su propio campo 'visit_time' en vez de created_at
    public $timestamps = false;

    /**
     * Campos que se pueden llenar masivamente.
     * Se usan al crear: Visit::create(['participant_id' => 1, 'stand_id' => 3, 'visit_time' => now()])
     */
    protected $fillable = ['participant_id','stand_id','visit_time'];
    
    /**
     * Casts: Le dice a Laravel cómo tratar ciertos campos.
     * 'visit_time' => 'datetime' → Laravel lo convierte automáticamente a un objeto Carbon
     *   lo que permite usar: $visit->visit_time->format('H:i'), ->diffInMinutes(), etc.
     */
    protected $casts = [
        'visit_time' => 'datetime',
    ];

    /**
     * Relación inversa: Esta visita PERTENECE A un participante.
     * Ejemplo: $visit->participant->nombre → nombre del participante de esta visita
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Relación inversa: Esta visita PERTENECE A un estand.
     * Ejemplo: $visit->stand->nombre → nombre del estand que visitó
     */
    public function stand()
    {
        return $this->belongsTo(Stand::class);
    }
}
