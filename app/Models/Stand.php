<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Models/Stand.php
|--------------------------------------------------------------------------
| Modelo Eloquent para la tabla 'stands'.
| Representa un estand de comida francesa en el evento.
|
| TABLA EN LA BD: stands
| CAMPOS:
|   - id          → ID autoincremental
|   - nombre      → Nombre del estand/platillo (ej: "Crème Brûlée")
|   - platillo    → Tipo de platillo (ej: "Crema flameada") — opcional
|   - descripcion → Descripción del platillo (texto largo) — opcional
|   - encargado   → Nombre del estudiante encargado — opcional
|   - created_at, updated_at → Timestamps automáticos de Laravel
|
| RELACIONES:
|   - Un estand TIENE MUCHAS visitas (hasMany Visit)
|
| Actualmente hay 8 estands (creados con StandSeeder).
| Se pueden agregar más desde la interfaz de admin o el seeder.
|--------------------------------------------------------------------------
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente.
     * Ejemplo: Stand::create(['nombre' => 'Crepê', 'platillo' => 'Crepa', ...])
     */
    protected $fillable = ['nombre', 'platillo', 'descripcion', 'encargado'];

    /**
     * Relación: Un estand TIENE MUCHAS visitas.
     * Ejemplo: $stand->visits → todas las visitas a este estand
     *          Stand::withCount('visits') → agrega 'visits_count' con el total de visitas
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
