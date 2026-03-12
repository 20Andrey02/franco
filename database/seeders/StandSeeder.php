<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stand;

class StandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stand::create([
            'nombre' => 'Estand Francia',
            'platillo' => 'Crème Brûlée',
            'descripcion' => 'Descubre la deliciosa gastronomía francesa',
            'encargado' => 'María García'
        ]);

        Stand::create([
            'nombre' => 'Estand Bélgica',
            'platillo' => 'Waffles Belgas',
            'descripcion' => 'Auténticos waffles de Bélgica',
            'encargado' => 'Juan López'
        ]);

        Stand::create([
            'nombre' => 'Estand Canadá',
            'platillo' => 'Jarabe de Arce',
            'descripcion' => 'Postres con delicioso jarabe de arce canadiense',
            'encargado' => 'Carlos Martín'
        ]);
    }
}
