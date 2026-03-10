<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@franco.mx',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Scanner Estand',
                'email' => 'scanner@franco.mx',
                'password' => bcrypt('password'),
                'role' => 'scanner',
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'user@franco.mx',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
            ['email' => $userData['email']],
                $userData
            );
        }
    }
}
