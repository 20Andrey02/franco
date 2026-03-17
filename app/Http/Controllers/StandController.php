<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/StandController.php
|--------------------------------------------------------------------------
| Controlador CRUD para los estands de comida francesa.
| Solo los administradores pueden acceder (middleware 'role:admin' en web.php).
|
| Cada estand tiene:
|   - nombre: nombre del platillo/estand (ej: "Crème Brûlée")
|   - platillo: tipo de platillo (ej: "Crema flameada")
|   - descripcion: texto descriptivo del platillo
|   - encargado: nombre del estudiante encargado del estand
|
| La relación con visitas se define en el modelo Stand (hasMany Visit).
| Usamos withCount('visits') para obtener cuántas visitas tiene cada estand
| sin hacer consultas extras.
|
| NOTA: Los 8 estands iniciales se crean con el seeder StandSeeder.
|       Si se quieren agregar más estands, también se puede desde la interfaz admin.
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stand;
use App\Models\Visit;
use App\Models\Participant;

class StandController extends Controller
{
    /**
     * Lista todos los estands con su conteo de visitas.
     * withCount('visits') agrega automáticamente 'visits_count' a cada estand.
     * Se ordenan alfabéticamente por nombre.
     */
    public function index()
    {
        $stands = Stand::withCount('visits')->orderBy('nombre')->get();
        return view('stands.index', compact('stands'));
    }

    /**
     * Muestra el formulario para crear un nuevo estand.
     */
    public function create()
    {
        return view('stands.create');
    }

    /**
     * Guarda un nuevo estand en la base de datos.
     * Solo 'nombre' es obligatorio — el resto es opcional.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',          // Nombre del estand (obligatorio)
            'platillo' => 'nullable|string|max:200',        // Tipo de platillo (opcional)
            'descripcion' => 'nullable|string|max:500',     // Descripción (opcional)
            'encargado' => 'nullable|string|max:200',       // Nombre del encargado (opcional)
        ]);

        $stand = Stand::create($data);
        return redirect()->route('stands.show', $stand)->with('success', 'Estand registrado correctamente.');
    }

    /**
     * Muestra el detalle de un estand: info básica, últimas 20 visitas y total.
     * Esta vista también tiene el formulario de escaneo QR (para registrar visitas).
     *
     * @param string $id  ID del estand
     */
    public function show(string $id)
    {
        $stand = Stand::findOrFail($id);

        // Obtener las últimas 20 visitas a este estand con los datos del participante
        // with('participant') → Eager Loading para evitar consultas N+1
        $recentVisits = Visit::with('participant')
            ->where('stand_id', $id)
            ->orderByDesc('visit_time')
            ->limit(20)
            ->get();

        // Total de visitas (sin límite, para mostrar el número completo)
        $totalVisits = Visit::where('stand_id', $id)->count();

        return view('stands.show', compact('stand', 'recentVisits', 'totalVisits'));
    }

    /**
     * Muestra el formulario de edición de un estand.
     */
    public function edit(string $id)
    {
        $stand = Stand::findOrFail($id);
        return view('stands.edit', compact('stand'));
    }

    /**
     * Actualiza los datos de un estand existente.
     */
    public function update(Request $request, string $id)
    {
        $stand = Stand::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'platillo' => 'nullable|string|max:200',
            'descripcion' => 'nullable|string|max:500',
            'encargado' => 'nullable|string|max:200',
        ]);
        $stand->update($data);
        return redirect()->route('stands.show', $stand)->with('success', 'Estand actualizado.');
    }

    /**
     * Elimina un estand de la base de datos.
     * CUIDADO: Las visitas asociadas se eliminan por CASCADE (definido en la migración).
     */
    public function destroy(string $id)
    {
        $stand = Stand::findOrFail($id);
        $stand->delete();
        return redirect()->route('stands.index')->with('success', 'Estand eliminado.');
    }
}
