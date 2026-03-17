<?php
/*
|--------------------------------------------------------------------------
| Archivo: app/Http/Controllers/AuthController.php
|--------------------------------------------------------------------------
| Controlador de autenticación — maneja login, logout y redirección por rol.
|
| FLUJO DE LOGIN:
| 1. Usuario va a /login → showLogin() muestra el formulario
| 2. Envía email + password → login() valida credenciales
| 3. Si son correctas → regenera sesión y redirige según su rol:
|    - admin   → /participants (lista de participantes)
|    - scanner → /scan (escáner QR)
|    - user    → /visitors/dashboard?code=FRANCO-XXXXXX (su panel personal)
| 4. Si son incorrectas → regresa al formulario con error
|
| NOTA IMPORTANTE: La contraseña para usuarios tipo "user" (participantes)
| es su código QR (ej: FRANCO-000101). Se crea automáticamente al registrar
| al participante en ParticipantController@store.
|
| CUENTAS DE PRUEBA:
| - admin@franco.mx / password (admin)
| - scanner@franco.mx / password (scanner)
| - user@franco.mx / password (user demo)
|
| NO OLVIDAR: Si cambias el campo 'correo' de la tabla participants,
| también hay que cambiar la búsqueda en redirectByRole() donde dice:
| Participant::where('correo', $user->email)
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Facade de autenticación de Laravel (Auth::attempt, Auth::check, etc.)

class AuthController extends Controller
{
    /**
     * Muestra la página de login.
     * Si el usuario ya está logueado, lo redirige directo a su panel según su rol.
     */
    public function showLogin()
    {
        // Auth::check() verifica si ya hay una sesión activa
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        // Si no está logueado, muestra la vista auth/login.blade.php
        return view('auth.login');
    }

    /**
     * Procesa el formulario de login (POST /login).
     * Valida email y password, intenta autenticar y redirige.
     */
    public function login(Request $request)
    {
        // validate() verifica que los campos cumplan las reglas
        // Si no cumplen, Laravel regresa automáticamente al formulario con los errores
        $credentials = $request->validate([
            'email' => 'required|email',      // Campo obligatorio y debe ser formato email
            'password' => 'required',          // Campo obligatorio
        ]);

        // Auth::attempt() busca en la tabla 'users' el email y compara el password (hasheado con bcrypt)
        // $request->boolean('remember') → si el checkbox "recordarme" está marcado, crea cookie de sesión persistente
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerar la sesión previene "session fixation" (ataque de seguridad)
            $request->session()->regenerate();
            // Redirige al panel correspondiente según el rol del usuario
            return $this->redirectByRole(Auth::user()->role);
        }

        // Si Auth::attempt() falló, regresamos con mensaje de error
        // back() → vuelve a la página anterior (/login)
        // withErrors() → agrega el error al $errors de Blade
        // onlyInput('email') → re-llena solo el campo email (por seguridad no re-llenamos password)
        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario (POST /logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();                            // Desloguea al usuario
        $request->session()->invalidate();         // Invalida la sesión actual
        $request->session()->regenerateToken();     // Regenera el token CSRF por seguridad
        return redirect('/');                       // Redirige al home
    }

    /**
     * Redirige al usuario según su rol después de loguearse.
     * Este es un método PRIVADO — solo lo usan los otros métodos de este controller.
     *
     * @param string $role  El rol del usuario ('admin', 'scanner', 'user')
     */
    private function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            // Los admins van a la lista de participantes
            return redirect()->route('participants.index');
        }
        if ($role === 'scanner') {
            // Los scanners van directamente al escáner QR
            return redirect()->route('scan.index');
        }
        if ($role === 'user') {
            // Los usuarios tipo "user" son participantes que se loguearon
            // Buscamos su registro de participante por su correo
            $user = Auth::user();
            $participant = \App\Models\Participant::where('correo', $user->email)->first();
            if ($participant && $participant->qr_code) {
                // Si encontramos al participante, lo mandamos a su dashboard con su código QR
                return redirect()->route('visitors.dashboard', ['code' => $participant->qr_code]);
            }
            // Si por alguna razón no se encontró el participante, va al home con error
            return redirect()->route('home')->with('error', 'No se encontró tu registro de participante.');
        }
        // Si el rol no es ninguno de los anteriores (no debería pasar), va al home
        return redirect()->route('home');
    }
}
