<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Models/User.php
|--------------------------------------------------------------------------
| Modelo Eloquent para la tabla 'users'.
| Representa las cuentas de usuario del SISTEMA (login con email/password).
|
| DIFERENCIA con Participant:
|   - User = cuenta de acceso al sistema (email, password, role)
|   - Participant = datos del asistente al evento (nombre, paterno, correo, qr_code)
|   - Están vinculados por email: User.email = Participant.correo
|
| TABLA EN LA BD: users
| CAMPOS:
|   - id, name, email (unique), email_verified_at, password, role,
|     remember_token, created_at, updated_at
|
| ROLES (campo 'role'):
|   - 'admin'   → Administrador. Accede a TODO: participantes, estands, reportes, escáner
|   - 'scanner' → Encargado de estand. Solo accede al escáner QR (/scan)
|   - 'user'    → Participante/visitante. Accede a su dashboard personal (/visitors/dashboard)
|
| TRAITS USADOS:
|   - HasApiTokens → Permite generar tokens API con Sanctum (no se usa mucho aquí)
|   - HasFactory   → Permite usar User::factory() para datos de prueba
|   - Notifiable   → Permite enviar notificaciones al usuario (email, etc.)
|
| CUENTAS DE PRUEBA (creadas por DatabaseSeeder):
|   - admin@franco.mx / password (admin)
|   - scanner@franco.mx / password (scanner)
|   - user@franco.mx / password (user)
|
| Los participantes también tienen cuenta User con:
|   - email = su correo / password = su código QR (ej: FRANCO-000042)
|
| NO OLVIDAR: Si agregas un nuevo rol, actualizar:
|   1. El middleware CheckRole (app/Http/Middleware/CheckRole.php)
|   2. La función redirectByRole() en AuthController
|   3. El sidebar de layouts/app.blade.php (menú por rol)
|--------------------------------------------------------------------------
*/

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Se podría activar si se quiere verificar emails
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Clase base para modelos de autenticación
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // HasApiTokens → para tokens API de Sanctum
    // HasFactory → para User::factory() en pruebas
    // Notifiable → para enviar notificaciones (email, SMS, etc.)
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos que se pueden llenar masivamente con create() o fill().
     * NOTA: 'role' está aquí para poder crear usuarios con rol desde el seeder.
     * Por seguridad, en producción podrías quitar 'role' de aquí y asignarlo manualmente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Campos que se OCULTAN cuando el modelo se convierte a array/JSON.
     * IMPORTANTE: Nunca exponer el password ni el token de recuerdo en APIs.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts: define cómo Laravel trata ciertos campos internamente.
     * 'email_verified_at' => 'datetime' → lo convierte a objeto Carbon (fecha/hora)
     * 'password' => 'hashed' → hashea automáticamente el password al asignarlo
     *   Esto significa que User::create(['password' => 'texto']) lo hashea solo.
     *   NOTA: Si usas bcrypt() manualmente al crear, se hashea dos veces (pero Laravel 10+ lo maneja).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ══════════════════════════════════════════════════
    // ══ Métodos helper para verificar el rol ═════════
    // ══════════════════════════════════════════════════
    // Se usan en las vistas Blade: @if(auth()->user()->isAdmin())
    // y en el middleware CheckRole.php

    /** ¿El usuario es administrador? */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** ¿El usuario es escáner (encargado de estand)? */
    public function isScanner(): bool
    {
        return $this->role === 'scanner';
    }

    /** ¿El usuario es visitante/participante? */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
