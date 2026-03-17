<?php
/*
|--------------------------------------------------------------------------
| Seeder Principal: DatabaseSeeder
|--------------------------------------------------------------------------
| Este es el seeder MAESTRO que se ejecuta con: php artisan db:seed
| O también: php artisan migrate:fresh --seed (borra todo y re-crea)
|
| ¿QUÉ ES UN SEEDER?
|   Es un archivo que llena la BD con datos iniciales o de prueba.
|   No modifica la estructura (eso lo hacen las migraciones), solo inserta filas.
|
| ORDEN DE EJECUCIÓN:
|   1. StandSeeder → Crea los 8 estands del evento
|   2. TestDataSeeder → Crea 20 participantes + visitas + encuestas de prueba
|   3. Este archivo → Crea los 3 usuarios administrativos
|
| USUARIOS DE PRUEBA:
|   admin@franco.mx   / password → Administrador (tiene acceso total)
|   scanner@franco.mx / password → Escáner QR (solo ve la página de escaneo)
|   user@franco.mx    / password → Visitante demo (ve su dashboard)
|
| NO OLVIDAR: Cambiar las contraseñas antes de producción.
|   En producción nunca uses 'password' como contraseña real.
|--------------------------------------------------------------------------
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Método principal del seeder. Ejecuta todo en orden.
     */
    public function run(): void
    {
        // 1. Primero crear los estands (deben existir antes de las visitas)
        $this->call(StandSeeder::class);

        // 2. Crear participantes, visitas y encuestas de prueba
        $this->call(TestDataSeeder::class);

        // 3. Crear los 3 usuarios administrativos del sistema
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@franco.mx',
                'password' => bcrypt('password'),   // bcrypt() encripta la contraseña
                'role' => 'admin',                  // Acceso total al sistema
            ],
            [
                'name' => 'Scanner Estand',
                'email' => 'scanner@franco.mx',
                'password' => bcrypt('password'),
                'role' => 'scanner',                // Solo acceso a /scan
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'user@franco.mx',
                'password' => bcrypt('password'),
                'role' => 'user',                   // Solo ve su dashboard
            ],
        ];

        // firstOrCreate: busca por email, si no existe → lo crea.
        // Esto permite correr el seeder varias veces sin duplicar usuarios.
        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],     // Buscar por este campo
                $userData                            // Si no existe, crear con estos datos
            );
        }
    }
}
