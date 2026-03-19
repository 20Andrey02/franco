<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Models/Participant.php
|--------------------------------------------------------------------------
| Modelo Eloquent para la tabla 'participants'.
| Representa a un asistente/visitante del evento de la Francofonía.
|
| TABLA EN LA BD: participants
| CAMPOS:
|   - id          → ID autoincremental (clave primaria)
|   - nombre      → Nombre del participante
|   - paterno     → Apellido paterno (NO se llama 'apellido')
|   - materno     → Apellido materno (opcional)
|   - ciudad      → Ciudad de origen (opcional)
|   - municipio   → Municipio (opcional)
|   - sexo        → M (Masculino), F (Femenino), O (Otro)
|   - correo      → Email del participante (ÚNICO, no se llama 'email')
|   - qr_code     → Código QR generado (ej: FR-042)
|   - created_at  → Fecha de registro (automático de Laravel)
|   - updated_at  → Fecha de última actualización (automático)
|
| RELACIONES:
|   - Un participante TIENE MUCHAS visitas (hasMany Visit)
|
| NO OLVIDAR: En esta tabla el correo se llama 'correo', NO 'email'.
|   Si buscas participantes por email, usa: Participant::where('correo', $email)
|   Esto es diferente a la tabla 'users' donde SÍ se llama 'email'.
|--------------------------------------------------------------------------
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    // HasFactory permite usar Participant::factory() para crear datos de prueba
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente con create() o fill().
     * Si no están en esta lista, Laravel los ignora por seguridad (Mass Assignment Protection).
     * NOTA: 'id', 'created_at', 'updated_at' NO van aquí porque Laravel los maneja automáticamente.
     */
    protected $fillable = [
        'nombre',
        'paterno',
        'materno',
        'ciudad',
        'municipio',
        'sexo',
        'correo',
        'qr_code',
    ];

    /**
     * Relación: Un participante TIENE MUCHAS visitas.
     * Laravel busca automáticamente la columna 'participant_id' en la tabla 'visits'.
     * Ejemplo de uso: $participante->visits → devuelve colección de todas sus visitas
     *                 $participante->visits()->count() → cuenta cuántas visitas tiene
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
