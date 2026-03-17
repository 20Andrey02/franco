{{--
|--------------------------------------------------------------------------
| VISTA: auth/login.blade.php  (Página de Login Dedicada para Móvil)
|--------------------------------------------------------------------------
|
| DESCRIPCIÓN:
|   Página de inicio de sesión independiente, pensada especialmente para
|   dispositivos móviles donde el modal de login de home.blade.php no es ideal.
|   Tiene un diseño con fondo degradado azul/rojo (colores de Francia).
|
| RUTA:       GET  /login  (nombre: 'login')          → Muestra el formulario
|             POST /login  (nombre: 'login')          → Procesa las credenciales
| CONTROLADOR: AuthController@showLogin / AuthController@login
|
| VARIABLES QUE RECIBE:
|   Ninguna directamente, pero usa session('error') para mostrar errores de login
|
| DIRECTIVAS BLADE USADAS:
|   @csrf           → Token CSRF obligatorio en el formulario POST
|   @if             → Para mostrar mensaje de error si las credenciales son incorrectas
|
| NO OLVIDAR:
|   - Vista STANDALONE (no usa @extends), tiene su propio HTML completo
|   - El formulario envía 'email' y 'password' vía POST a route('login')
|   - Después de un login exitoso, redirige según el rol:
|     admin → /participants, scanner → /scan
|   - Enlace "Volver al inicio" lleva a route('home')
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Francofonía</title>
    <link href="{{ asset('css/fonts-local.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <style>
        :root { --blue-dark: #002395; --blue-mid: #0035b5; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header img { height: 60px; width: 60px; margin-bottom: 12px; }
        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
        }
        .login-header p { opacity: 0.85; font-size: 0.9rem; margin: 8px 0 0; }
        .login-body { padding: 30px; }
        .form-control { padding: 12px 16px; font-size: 1rem; border-radius: 10px; }
        .form-control:focus { border-color: var(--blue-dark); box-shadow: 0 0 0 0.2rem rgba(0,35,149,0.25); }
        .form-label { font-weight: 600; color: #333; }
        .btn-login {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
        }
        .btn-login:hover, .btn-login:active { background: var(--blue-dark); color: white; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link:hover { color: var(--blue-dark); }
        .input-group .btn { border-radius: 0 10px 10px 0; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo-francofonia.png') }}" alt="Francofonía">
            <h1>Iniciar Sesión</h1>
            <p>Feria Gastronómica de la Francofonía</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" inputmode="email">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="let p=document.getElementById('password');p.type=p.type==='password'?'text':'password';">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-login">Ingresar</button>
            </form>
            <a href="{{ route('home') }}" class="back-link"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
        </div>
    </div>
</body>
</html>
