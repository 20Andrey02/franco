<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Models/Survey.php
|--------------------------------------------------------------------------
| Modelo Eloquent para la tabla 'surveys'.
| Representa la encuesta de satisfacción que llena cada participante.
| Cada participante solo puede llenar UNA encuesta (se valida en SurveyController).
|
| TABLA EN LA BD: surveys
| CAMPOS:
|   - id              → ID autoincremental
|   - participant_id  → FK → tabla 'participants' (ON DELETE CASCADE)
|   - q1 a q5         → Respuestas a las 5 preguntas (tinyInteger, valores 1-5)
|   - comentarios     → Texto de comentarios opcionales
|   - created_at, updated_at → Timestamps automáticos
|
| PREGUNTAS (escala Likert 1-5):
|   q1: ¿Qué tal fue tu experiencia en el evento?
|   q2: ¿Disfrutaste de la comida y bebidas?
|   q3: ¿Los stands estaban bien organizados?
|   q4: ¿Recomendarías este evento a otros?
|   q5: ¿Volverías a un evento similar?
|
| MÉTODOS HELPER:
|   - getAverageScore() → promedio de las 5 respuestas (ej: 4.2)
|   - getSatisfactionLevel() → texto descriptivo ("Excelente", "Bueno", etc.)
|
| RELACIÓN: Una encuesta PERTENECE A un participante (belongsTo)
|--------------------------------------------------------------------------
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente.
     * q1 a q5 son las 5 preguntas de la encuesta (valores del 1 al 5).
     */
    protected $fillable = ['participant_id','q1','q2','q3','q4','comentarios'];

    /**
     * Relación: Esta encuesta PERTENECE A un participante.
     * Ejemplo: $survey->participant->nombre → nombre del participante que la llenó
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Calcula el promedio de las 5 respuestas.
     * Retorna un float entre 1.0 y 5.0.
     * Ejemplo: si q1=5, q2=4, q3=5, q4=4, q5=5 → promedio = 4.6
     */
    public function getAverageScore(): float
    {
        return ($this->q1 + $this->q2 + $this->q3 + $this->q4) / 4;
    }

    /**
     * Convierte el promedio numérico a un texto descriptivo.
     * Escala 0-10.
     *
     * Escala:
     *   9.0 - 10   → "Excelente"
     *   7.0 - 8.99 → "Muy Bueno"
     *   5.0 - 6.99 → "Bueno"
     *   3.0 - 4.99 → "Regular"
     *   0.0 - 2.99 → "Malo"
     */
    public function getSatisfactionLevel(): string
    {
        $avg = $this->getAverageScore();
        
        if ($avg >= 9) return 'Excelente';
        if ($avg >= 7) return 'Muy Bueno';
        if ($avg >= 5) return 'Bueno';
        if ($avg >= 3) return 'Regular';
        return 'Malo';
    }
}
