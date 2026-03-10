<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Francofonía</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --blue-dark: #002395;
            --red-fr:    #ED2939;
            --gold:      #d4af37;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a0e2e;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg-animated {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 15% 30%, rgba(0,35,149,0.7) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 70%, rgba(237,41,57,0.4) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(212,175,55,0.12) 0%, transparent 60%);
        }

        .login-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 440px;
            padding: 24px;
        }

        .login-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 48px 40px;
            backdrop-filter: blur(20px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
        }

        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 36px;
        }

        .login-logo img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 50%;
            filter: drop-shadow(0 6px 20px rgba(0,35,149,0.5));
            margin-bottom: 16px;
        }

        .login-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
            margin: 0;
        }

        .login-logo small {
            color: rgba(255,255,255,0.45);
            font-size: 0.72rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .flag-bar {
            display: flex;
            height: 3px;
            border-radius: 2px;
            overflow: hidden;
            margin: 20px 0 32px;
        }
        .f-blue  { flex: 1; background: #002395; }
        .f-white { flex: 1; background: rgba(255,255,255,0.7); }
        .f-red   { flex: 1; background: #ED2939; }

        .form-label-login {
            color: rgba(255,255,255,0.7);
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-login {
            width: 100%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            color: #fff;
            padding: 13px 16px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .form-control-login::placeholder { color: rgba(255,255,255,0.3); }

        .form-control-login:focus {
            border-color: rgba(77,126,255,0.6);
            background: rgba(255,255,255,0.12);
            box-shadow: 0 0 0 3px rgba(0,35,149,0.25);
        }

        .form-control-login.is-invalid {
            border-color: rgba(237,41,57,0.7);
        }

        .invalid-msg {
            color: #ff8090;
            font-size: 0.78rem;
            margin-top: 5px;
        }

        .form-check-login {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0 24px;
        }

        .form-check-login input[type="checkbox"] { accent-color: #4d7eff; width: 16px; height: 16px; }
        .form-check-login label { color: rgba(255,255,255,0.6); font-size: 0.83rem; cursor: pointer; }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--blue-dark), #1a4de8);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 6px 24px rgba(0,35,149,0.45);
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(0,35,149,0.6);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: rgba(255,255,255,0.45);
            font-size: 0.82rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: rgba(255,255,255,0.8); }

        .mb-form { margin-bottom: 18px; }
    </style>
</head>
<body>

<div class="bg-animated"></div>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Logo -->
        <div class="login-logo">
            <img src="{{ asset('images/logo-francofonia.png') }}" alt="Logo Francofonía">
            <h1>Francofonía</h1>
            <small>Sistema de Estands</small>
        </div>

        <div class="flag-bar">
            <div class="f-blue"></div>
            <div class="f-white"></div>
            <div class="f-red"></div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf

            <div class="mb-form">
                <label for="email" class="form-label-login">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control-login {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                    autocomplete="email"
                    autofocus
                >
                @error('email')
                    <p class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-form">
                <label for="password" class="form-label-login">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control-login {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-check-login">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Recordar sesión</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
            </button>
        </form>

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Volver a la página principal
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
