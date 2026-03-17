<?php
/*
|--------------------------------------------------------------------------
| Seeder: StandSeeder
|--------------------------------------------------------------------------
| Crea los 8 estands del evento de gastronomía francesa.
| Cada estand tiene: nombre en francés, tipo de platillo, descripción y encargado.
|
| Se ejecuta como parte de DatabaseSeeder (php artisan db:seed)
|
| Usa updateOrCreate para que si ya existe un estand con el mismo nombre,
| simplemente actualice sus datos en vez de crear uno duplicado.
| Esto es útil al correr el seeder varias veces durante desarrollo.
|
| NO OLVIDAR: Si se agregan o quitan estands, actualizar este archivo.
|   Los estands son los 8 del evento del 20 de marzo de 2026.
|--------------------------------------------------------------------------
*/

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stand;

class StandSeeder extends Seeder
{
    /**
     * Crear los 8 estands de comida francesa.
     */
    public function run(): void
    {
        // Arreglo con los datos reales de los 8 estands del evento
        $stands = [
            [
                'nombre' => 'Crepê',                           // Estand 1
                'platillo' => 'Crepa',
                'descripcion' => 'Delicada crepa francesa, un clásico de la gastronomía gala.',
                'encargado' => 'Adriana García Malpica',
            ],
            [
                'nombre' => 'La Madeleine à la Veilleuse',     // Estand 2
                'platillo' => 'Magdalena',
                'descripcion' => 'Tradicional magdalena francesa, suave y esponjosa.',
                'encargado' => 'Alexa Sinaí Santiago Villanueva',
            ],
            [
                'nombre' => 'Quiche Lorraine',                 // Estand 3
                'platillo' => 'Pastel',
                'descripcion' => 'Pastel salado de origen francés con huevo, crema y tocino.',
                'encargado' => 'Mildred Zoé Gómez Bautista',
            ],
            [
                'nombre' => 'Croquenbouche',                   // Estand 4
                'platillo' => 'Profiterol',
                'descripcion' => 'Torre de profiteroles cubiertos de caramelo, un postre espectacular.',
                'encargado' => 'José Emilio Hernández Romero',
            ],
            [
                'nombre' => 'Crème Brûlée',                    // Estand 5
                'platillo' => 'Crema flameada',
                'descripcion' => 'Crema pastelera con una crujiente capa de caramelo flameado.',
                'encargado' => 'Selina Maldonado López',
            ],
            [
                'nombre' => 'Canapé',                          // Estand 6
                'platillo' => 'Canape',
                'descripcion' => 'Elegantes bocadillos franceses sobre pan tostado.',
                'encargado' => 'Alondra Pardiñas Ordoñez',
            ],
            [
                'nombre' => 'Croque Monsieur y Croque Madame', // Estand 7
                'platillo' => 'Sandwich',
                'descripcion' => 'Sándwich francés gratinado con jamón y queso, clásico de la cocina parisina.',
                'encargado' => 'José Guadalupe Rivera Quezada',
            ],
            [
                'nombre' => 'Croissant',                       // Estand 8
                'platillo' => 'Pan',
                'descripcion' => 'Icónico pan hojaldrado francés, crujiente por fuera y suave por dentro.',
                'encargado' => 'Ivan Atzin Santes',
            ],
        ];

        // updateOrCreate: busca por 'nombre', si existe → actualiza, si no → crea
        // Así podemos correr el seeder muchas veces sin duplicar
        foreach ($stands as $stand) {
            Stand::updateOrCreate(
                ['nombre' => $stand['nombre']],     // Condición de búsqueda
                $stand                              // Datos para crear/actualizar
            );
        }
    }
}
