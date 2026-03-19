<?php
/*
|--------------------------------------------------------------------------
| Seeder: TestDataSeeder
|--------------------------------------------------------------------------
| Crea datos FICTICIOS de prueba para desarrollo y demostración.
| NO es para producción — son datos inventados para probar el sistema.
|
| ¿QUÉ CREA?
|   1. 20 participantes ficticios con nombres reales de la región
|   2. Un usuario (role='user') por cada participante (contraseña = su QR)
|   3. Visitas aleatorias (3-6 estands por participante)
|   4. Encuestas con calificaciones variadas y comentarios opcionales
|
| SE EJECUTA CON:
|   php artisan db:seed           → corre todos los seeders
|   php artisan db:seed --class=TestDataSeeder → solo este
|
| COMPORTAMIENTO: Usa firstOrCreate para no duplicar datos si se corre
|   más de una vez. Es "idempotente" (ejecutarlo N veces da el mismo resultado).
|
| NO OLVIDAR: Estos datos son para pruebas. En el evento real, los
|   participantes se registran desde el formulario público.
|--------------------------------------------------------------------------
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Participant;
use App\Models\User;
use App\Models\Stand;
use App\Models\Survey;
use App\Models\Visit;
use Carbon\Carbon;   // Librería para manejar fechas fácilmente

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════
        // SECCIÓN 1: Crear 20 participantes ficticios
        // Nombres y correos inventados pero realistas
        // ═══════════════════════════════════════════════════════════
        $participantsData = [
            ['nombre' => 'María',    'paterno' => 'López',     'materno' => 'García',    'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'F', 'correo' => 'maria.lopez@gmail.com'],
            ['nombre' => 'Carlos',   'paterno' => 'Hernández', 'materno' => 'Ruiz',      'ciudad' => 'Papantla',         'municipio' => 'Papantla',         'sexo' => 'M', 'correo' => 'carlos.hdz@gmail.com'],
            ['nombre' => 'Ana',      'paterno' => 'Martínez',  'materno' => 'Flores',    'ciudad' => 'Tecolutla',        'municipio' => 'Tecolutla',        'sexo' => 'F', 'correo' => 'ana.martinez@hotmail.com'],
            ['nombre' => 'Luis',     'paterno' => 'Pérez',     'materno' => 'Sánchez',   'ciudad' => 'Poza Rica',        'municipio' => 'Poza Rica',        'sexo' => 'M', 'correo' => 'luis.perez@yahoo.com'],
            ['nombre' => 'Sofía',    'paterno' => 'Ramírez',   'materno' => 'Torres',    'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'F', 'correo' => 'sofia.ramirez@gmail.com'],
            ['nombre' => 'Diego',    'paterno' => 'Morales',   'materno' => 'Vega',      'ciudad' => 'Tuxpan',           'municipio' => 'Tuxpan',           'sexo' => 'M', 'correo' => 'diego.morales@outlook.com'],
            ['nombre' => 'Valentina','paterno' => 'Cruz',      'materno' => 'Mendoza',   'ciudad' => 'Papantla',         'municipio' => 'Papantla',         'sexo' => 'F', 'correo' => 'vale.cruz@gmail.com'],
            ['nombre' => 'Andrés',   'paterno' => 'Jiménez',   'materno' => 'Ortiz',     'ciudad' => 'Poza Rica',        'municipio' => 'Poza Rica',        'sexo' => 'M', 'correo' => 'andres.jimenez@gmail.com'],
            ['nombre' => 'Camila',   'paterno' => 'Reyes',     'materno' => 'Luna',      'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'F', 'correo' => 'camila.reyes@hotmail.com'],
            ['nombre' => 'Fernando', 'paterno' => 'Díaz',      'materno' => 'Campos',    'ciudad' => 'Tecolutla',        'municipio' => 'Tecolutla',        'sexo' => 'M', 'correo' => 'fernando.diaz@gmail.com'],
            ['nombre' => 'Isabella', 'paterno' => 'Vargas',    'materno' => 'Rojas',     'ciudad' => 'Tuxpan',           'municipio' => 'Tuxpan',           'sexo' => 'F', 'correo' => 'isabella.vargas@yahoo.com'],
            ['nombre' => 'Roberto',  'paterno' => 'Castillo',  'materno' => 'Guerrero',  'ciudad' => 'Papantla',         'municipio' => 'Papantla',         'sexo' => 'M', 'correo' => 'roberto.castillo@gmail.com'],
            ['nombre' => 'Daniela',  'paterno' => 'Soto',      'materno' => 'Herrera',   'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'F', 'correo' => 'daniela.soto@outlook.com'],
            ['nombre' => 'Javier',   'paterno' => 'Ríos',      'materno' => 'Aguirre',   'ciudad' => 'Poza Rica',        'municipio' => 'Poza Rica',        'sexo' => 'M', 'correo' => 'javier.rios@gmail.com'],
            ['nombre' => 'Renata',   'paterno' => 'Medina',    'materno' => 'Chávez',    'ciudad' => 'Tecolutla',        'municipio' => 'Tecolutla',        'sexo' => 'F', 'correo' => 'renata.medina@gmail.com'],
            ['nombre' => 'Emilio',   'paterno' => 'Guzmán',    'materno' => 'Rangel',    'ciudad' => 'Tuxpan',           'municipio' => 'Tuxpan',           'sexo' => 'M', 'correo' => 'emilio.guzman@hotmail.com'],
            ['nombre' => 'Lucía',    'paterno' => 'Fernández', 'materno' => 'Ibarra',    'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'F', 'correo' => 'lucia.fernandez@gmail.com'],
            ['nombre' => 'Sebastián','paterno' => 'Navarro',   'materno' => 'Delgado',   'ciudad' => 'Papantla',         'municipio' => 'Papantla',         'sexo' => 'M', 'correo' => 'sebastian.navarro@yahoo.com'],
            ['nombre' => 'Paula',    'paterno' => 'Estrada',   'materno' => 'Cortés',    'ciudad' => 'Poza Rica',        'municipio' => 'Poza Rica',        'sexo' => 'F', 'correo' => 'paula.estrada@gmail.com'],
            ['nombre' => 'Héctor',   'paterno' => 'Salazar',   'materno' => 'Peña',      'ciudad' => 'Gutiérrez Zamora', 'municipio' => 'Gutiérrez Zamora', 'sexo' => 'M', 'correo' => 'hector.salazar@outlook.com'],
        ];

        // Obtener todos los estands (deben existir, StandSeeder corre antes)
        $stands = Stand::all();
        $counter = 1;  // Contador para generar QR codes \u00fanicos

        foreach ($participantsData as $data) {
            // Generar código QR: FR-101, FR-102, etc.
            // str_pad rellena con ceros a la izquierda hasta 3 dígitos
            $qrCode = 'FR-' . str_pad($counter + 100, 3, '0', STR_PAD_LEFT);

            // firstOrCreate: busca por 'correo', si no existe lo crea
            // array_merge combina los datos del participante + su qr_code
            $participant = Participant::firstOrCreate(
                ['correo' => $data['correo']],
                array_merge($data, ['qr_code' => $qrCode])
            );

            // Crear un usuario vinculado al participante
            // La contrase\u00f1a es el MISMO c\u00f3digo QR (para que puedan hacer login)
            User::firstOrCreate(
                ['email' => $data['correo']],   // email del User = correo del Participant
                [
                    'name' => $data['nombre'] . ' ' . $data['paterno'],
                    'email' => $data['correo'],
                    'password' => bcrypt($participant->qr_code),  // Contrase\u00f1a = QR code
                    'role' => 'user',           // Rol de visitante
                ]
            );

            $counter++;
        }

        // \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
        // SECCI\u00d3N 2: Crear visitas aleatorias de prueba\n        // Simula que cada participante visit\u00f3 entre 3 y 6 estands\n        // \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
        $participants = Participant::all();\n        // Fecha base: 20 de marzo de 2026 a las 10:00 AM (d\u00eda del evento)
        $baseDate = Carbon::create(2026, 3, 20, 10, 0, 0);

        foreach ($participants as $participant) {
            // Cada participante visita entre 3 y 6 stands aleatorios
            $visitCount = rand(3, min(6, $stands->count()));
            $visitedStands = $stands->random($visitCount);  // Selecci\u00f3n aleatoria
            $minuteOffset = 0;

            foreach ($visitedStands as $stand) {
                $minuteOffset += rand(5, 20);  // 5 a 20 minutos entre cada visita

                Visit::firstOrCreate([
                    'participant_id' => $participant->id,
                    'stand_id' => $stand->id,
                ], [
                    // copy() crea una copia del Carbon para no modificar el original
                    // addMinutes() suma minutos para simular horarios diferentes
                    'visit_time' => $baseDate->copy()->addMinutes($minuteOffset + ($participant->id * 8)),
                ]);
            }
        }

        // \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
        // SECCI\u00d3N 3: Crear encuestas de satisfacci\u00f3n de prueba
        // Calificaciones variadas (1-5) con comentarios opcionales
        // \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
        // Comentarios opcionales (null = no dej\u00f3 comentario)
        $comentarios = [
            '¡Excelente evento! Los platillos estuvieron deliciosos.',
            'Me encantó la crème brûlée, fue mi favorita de toda la feria.',
            'Muy buena organización, felicidades a todos los estudiantes.',
            'Los croissants estaban increíbles, como en una panadería francesa.',
            'Ojalá hagan más eventos así, la comida estuvo espectacular.',
            'El croquenbouche fue impresionante, tanto visualmente como de sabor.',
            'Bonita experiencia, aprendí mucho sobre la gastronomía francesa.',
            'Las crepas estuvieron muy ricas, me comí dos.',
            null,
            null,
            null,
            null,
            null,
            'Muy interesante conocer la cultura francófona a través de la comida.',
            null,
            'El quiche lorraine superó mis expectativas, exquisito.',
            null,
            'Gran ambiente, buena música y mejor comida. ¡Volveré!',
            null,
            'Los canapés estuvieron elegantes y sabrosos, muy profesional.',
        ];

        // Calificaciones predefinidas: arreglos de [q1, q2, q3, q4, q5]
        // Variadas para que las estad\u00edsticas se vean realistas (no todo 5/5)
        $ratings = [
            [5, 5, 4, 5, 5],
            [4, 5, 5, 4, 5],
            [5, 4, 4, 5, 4],
            [3, 4, 4, 3, 4],
            [5, 5, 5, 5, 5],
            [4, 3, 4, 4, 3],
            [5, 5, 5, 4, 5],
            [4, 4, 3, 4, 4],
            [5, 4, 5, 5, 4],
            [3, 3, 4, 3, 3],
            [5, 5, 4, 5, 5],
            [4, 4, 5, 4, 4],
            [5, 5, 5, 5, 4],
            [4, 5, 4, 4, 5],
            [3, 4, 3, 4, 3],
            [5, 4, 5, 5, 5],
            [4, 3, 4, 3, 4],
            [5, 5, 5, 5, 5],
            [4, 4, 4, 5, 4],
            [5, 5, 4, 4, 5],
        ];

        // Crear una encuesta por participante con calificaciones y comentarios variados
        foreach ($participants as $index => $participant) {
            $i = $index % count($ratings);  // Ciclar entre los ratings disponibles

            Survey::firstOrCreate(
                ['participant_id' => $participant->id],  // Solo una encuesta por participante
                [
                    'q1' => $ratings[$i][0],             // Experiencia general
                    'q2' => $ratings[$i][1],             // Comida y bebidas
                    'q3' => $ratings[$i][2],             // Organizaci\u00f3n
                    'q4' => $ratings[$i][3],             // Recomendaci\u00f3n
                    'q5' => $ratings[$i][4],             // Repetir\u00eda
                    'comentarios' => $comentarios[$i] ?? null,  // null si no hay comentario
                ]
            );
        }

        // Mensaje de confirmaci\u00f3n en consola al terminar
        if ($this->command) {
            $this->command->info('\u2713 Datos de prueba creados: ' . $participants->count() . ' participantes, visitas y encuestas.');
        }
    }
}
