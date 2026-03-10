<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Francofonía — Evento Cultural</title>

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
            --blue-mid:  #0035b5;
            --white-fr:  #ffffff;
            --red-fr:    #ED2939;
            --gold:      #d4af37;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a0e2e;
            overflow-x: hidden;
        }

        /* ── Animated background ── */
        .bg-animated {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(0, 35, 149, 0.6) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(237, 41, 57, 0.4) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 60%);
        }

        .bg-animated::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(255,255,255,0.015) 2px,
                rgba(255,255,255,0.015) 4px
            );
        }

        /* ── Navbar ── */
        .navbar-franco {
            position: relative;
            z-index: 10;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
        }

        .navbar-brand-franco {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-flag {
            width: 4px;
            height: 32px;
            border-radius: 2px;
            background: linear-gradient(to bottom, #002395 33%, #fff 33% 66%, #ED2939 66%);
        }

        .brand-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .brand-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
            display: block;
            margin-top: -2px;
        }

        .btn-login-nav {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 50px;
            padding: 9px 24px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            backdrop-filter: blur(10px);
            transition: all 0.2s;
        }

        .btn-login-nav:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            z-index: 5;
            min-height: calc(100vh - 81px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 60px 24px;
            max-width: 860px;
            margin: 0 auto;
        }

        /* ── Logo container ── */
        .logo-wrapper {
            position: relative;
            width: 180px;
            height: 180px;
            margin-bottom: 40px;
            animation: floatLogo 5s ease-in-out infinite;
        }

        .logo-wrapper::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            background: conic-gradient(
                #002395 0deg 72deg,
                #fff 72deg 144deg,
                #ED2939 144deg 216deg,
                #d4af37 216deg 288deg,
                #002395 288deg 360deg
            );
            opacity: 0.3;
            filter: blur(18px);
            animation: rotateShadow 8s linear infinite;
        }

        .logo-img {
            width: 180px;
            height: 180px;
            object-fit: contain;
            border-radius: 50%;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 8px 32px rgba(0,35,149,0.5));
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        @keyframes rotateShadow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ── Text ── */
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.35);
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 22px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 7vw, 4.8rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .hero-title .accent-blue { color: #4d7eff; }
        .hero-title .accent-red  { color: #ff6b7a; }

        .hero-subtitle {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.6);
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .hero-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.72);
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        /* ── CTA Buttons ── */
        .cta-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--blue-dark) 0%, #1a4de8 100%);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 15px 34px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(0, 35, 149, 0.5);
            transition: all 0.25s;
        }

        .btn-cta-primary:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 16px 40px rgba(0, 35, 149, 0.65);
        }

        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 50px;
            padding: 15px 34px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: all 0.25s;
        }

        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.14);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── Info Cards ── */
        .info-section {
            position: relative;
            z-index: 5;
            padding: 80px 24px;
            background: rgba(255,255,255,0.03);
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .info-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 32px 28px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .info-card:hover {
            background: rgba(255,255,255,0.09);
            transform: translateY(-4px);
            border-color: rgba(255,255,255,0.2);
        }

        .info-card-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px;
        }

        .icon-blue { background: rgba(0,35,149,0.4); color: #7ba7ff; }
        .icon-gold  { background: rgba(212,175,55,0.2); color: var(--gold); }
        .icon-red   { background: rgba(237,41,57,0.2); color: #ff8090; }

        .info-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .info-card p {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
        }

        /* ── Roles Section ── */
        .roles-section {
            position: relative;
            z-index: 5;
            padding: 80px 24px;
        }

        .section-title {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .section-sub {
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            margin-bottom: 48px;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            max-width: 900px;
            margin: 0 auto;
        }

        .role-card {
            border-radius: 20px;
            padding: 36px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .role-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.04));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .role-card:hover { transform: translateY(-6px); }

        .role-admin   { background: linear-gradient(135deg, rgba(0,35,149,0.4), rgba(0,70,200,0.2)); }
        .role-scanner { background: linear-gradient(135deg, rgba(212,175,55,0.3), rgba(184,134,11,0.15)); }
        .role-user    { background: linear-gradient(135deg, rgba(237,41,57,0.25), rgba(192,57,43,0.12)); }

        .role-icon {
            font-size: 2.8rem;
            margin-bottom: 16px;
            display: block;
        }

        .role-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .role-card p {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.7;
        }

        .role-badge {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 50px;
            margin-top: 16px;
        }

        .badge-admin   { background: rgba(0,35,149,0.5); color: #7ba7ff; border: 1px solid rgba(77,126,255,0.3); }
        .badge-scanner { background: rgba(212,175,55,0.25); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
        .badge-user    { background: rgba(237,41,57,0.25); color: #ff8090; border: 1px solid rgba(255,128,144,0.3); }

        /* ── Footer ── */
        .footer {
            position: relative;
            z-index: 5;
            text-align: center;
            padding: 32px 24px;
            border-top: 1px solid rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
        }

        /* ── Alert ── */
        .alert-home {
            max-width: 500px;
            margin: 0 auto 24px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-danger-home {
            background: rgba(237,41,57,0.15);
            border: 1px solid rgba(237,41,57,0.35);
            color: #ff8090;
        }

        @media (max-width: 576px) {
            .navbar-franco { padding: 16px 20px; }
            .logo-wrapper { width: 140px; height: 140px; }
            .logo-img { width: 140px; height: 140px; }
        }
    </style>
</head>
<body>

<div class="bg-animated"></div>

<!-- NAVBAR -->
<nav class="navbar-franco">
    <a href="{{ route('home') }}" class="navbar-brand-franco">
        <div class="brand-flag"></div>
        <div>
            <span class="brand-text">Francofonía</span>
            <span class="brand-sub">Evento Cultural</span>
        </div>
    </a>
    @auth
        <a href="{{ auth()->user()->isAdmin() ? route('participants.index') : route('scan.index') }}" class="btn-login-nav">
            <i class="bi bi-grid-1x2-fill me-1"></i> Panel
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-login-nav">
            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
        </a>
    @endauth
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">

        @if(session('error'))
            <div class="alert-home alert-danger-home">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo-francofonia.png') }}" alt="Logo Francofonía" class="logo-img">
        </div>

        <span class="hero-tag">
            <i class="bi bi-star-fill" style="font-size:.6rem;"></i>
            Evento Cultural Francófono
        </span>

        <h1 class="hero-title">
            <span class="accent-blue">Franco</span><span class="accent-red">fonía</span>
        </h1>

        <p class="hero-subtitle">Festival de las lenguas &amp; culturas</p>

        <p class="hero-desc">
            Un encuentro único donde la cultura francófona cobra vida. Explora gastronomía,
            arte, música y tradiciones de los países francófonos del mundo. Regístrate,
            obtén tu código QR y visita los estands de nuestros estudiantes.
        </p>

        <div class="cta-group">
            <a href="{{ route('login') }}" class="btn-cta-primary">
                <i class="bi bi-box-arrow-in-right"></i> Acceder al sistema
            </a>
            <a href="#info" class="btn-cta-secondary">
                <i class="bi bi-info-circle"></i> Más información
            </a>
        </div>
    </div>
</section>

<!-- INFO CARDS -->
<section class="info-section" id="info">
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-icon icon-blue">
                <i class="bi bi-qr-code"></i>
            </div>
            <h3>Registro con QR</h3>
            <p>Cada participante recibe un código QR único para ser identificado al visitar los estands del evento.</p>
        </div>
        <div class="info-card">
            <div class="info-card-icon icon-gold">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <h3>Estands Culturales</h3>
            <p>Descubre hasta 5 estands gestionados por estudiantes con gastronomía y cultura de países francófonos.</p>
        </div>
        <div class="info-card">
            <div class="info-card-icon icon-red">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <h3>Seguimiento en Tiempo Real</h3>
            <p>Los organizadores monitorean las visitas en vivo y generan reportes del estand más visitado.</p>
        </div>
    </div>
</section>

<!-- ROLES SECTION -->
<section class="roles-section">
    <h2 class="section-title">¿Cómo funciona el sistema?</h2>
    <p class="section-sub">Tres tipos de acceso según tu rol en el evento</p>

    <div class="roles-grid">
        <div class="role-card role-admin">
            <span class="role-icon">🛡️</span>
            <h3>Administrador</h3>
            <p>Gestiona participantes, estands y reportes. Acceso completo al sistema de control del evento.</p>
            <span class="role-badge badge-admin">Admin</span>
        </div>
        <div class="role-card role-scanner">
            <span class="role-icon">📷</span>
            <h3>Escáner de QR</h3>
            <p>Personal de los estands que escanea los códigos QR de los participantes para registrar sus visitas.</p>
            <span class="role-badge badge-scanner">Scanner</span>
        </div>
        <div class="role-card role-user">
            <span class="role-icon">🎟️</span>
            <h3>Participante</h3>
            <p>Asistente al evento que utiliza su código QR para visitar los diferentes estands culturales.</p>
            <span class="role-badge badge-user">Usuario</span>
        </div>
    </div>
</section>

<footer class="footer">
    &copy; {{ date('Y') }} Francofonía — Evento Cultural &nbsp;|&nbsp; Sistema de Gestión de Estands
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
