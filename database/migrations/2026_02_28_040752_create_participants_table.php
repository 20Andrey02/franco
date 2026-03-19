<?php
/*
|--------------------------------------------------------------------------
| Migración: create_participants_table
|--------------------------------------------------------------------------
| Crea la tabla 'participants' para almacenar los asistentes al evento.
|
| ¿QUÉ ES UNA MIGRACIÓN?
|   Es un archivo PHP que define cambios en la estructura de la base de datos.
|   Se ejecutan con: php artisan migrate
|   Se revierten con: php artisan migrate:rollback
|   Esto permite tener "control de versiones" de la BD.
|
| NOMBRE DEL ARCHIVO:
|   2026_02_28_040752_create_participants_table.php
|   └─ fecha ─────┘ └ hora ┘ └─── descripción ─────┘
|   La fecha determina el ORDEN de ejecución (se ejecutan cronológicamente).
|
| COLUMNAS:
|   - nombre: string obligatorio (ej: "María")
|   - paterno: string obligatorio (ej: "López") — NO se llama 'apellido'
|   - materno: string nullable (ej: "García")
|   - ciudad, municipio: strings nullable (de dónde viene el participante)
|   - sexo: enum('M','F','O') con default 'O' (Masculino, Femenino, Otro)
|   - correo: string único (se usa para vincular con la tabla 'users')
|   - qr_code: string nullable (se genera después de crear: FR-042)
|
| NO OLVIDAR: El campo se llama 'correo', NO 'email'. Y 'paterno', NO 'apellido'.
|   Esto es diferente a la tabla 'users' donde sí se llama 'email'.
|--------------------------------------------------------------------------
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración: crear la tabla.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();                                    // Columna 'id' autoincremental (bigint unsigned)
            $table->string('nombre');                        // Nombre del participante (obligatorio)
            $table->string('paterno');                       // Apellido paterno (obligatorio)
            $table->string('materno')->nullable();           // Apellido materno (opcional, puede ser null)
            $table->string('ciudad')->nullable();            // Ciudad de procedencia (opcional)
            $table->string('municipio')->nullable();         // Municipio (opcional)
            $table->enum('sexo', ['M','F','O'])->default('O'); // Sexo con 3 opciones, default Otro
            $table->string('correo')->unique();              // Email único (no puede repetirse)
            $table->string('qr_code')->nullable();           // Código QR (se genera después de crear)
            $table->timestamps();                            // Crea 'created_at' y 'updated_at' automáticamente
        });
    }

    /**
     * Revertir la migración: eliminar la tabla.
     * Se ejecuta con: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
