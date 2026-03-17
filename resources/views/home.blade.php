{{--
|--------------------------------------------------------------------------
| VISTA: home.blade.php  (Página Principal Pública)
|--------------------------------------------------------------------------
|
| DESCRIPCIÓN:
|   Página de inicio pública del evento "Foire des Saveurs de la Francophonie".
|   NO requiere autenticación. Cualquier persona puede verla.
|   Contiene:
|     - Sección hero con título del evento y fecha (20 de marzo de 2026)
|     - Tarjetas "flip" (voltean al pasar el mouse) mostrando cada stand con su platillo
|     - Modal de login para que admin/scanner puedan iniciar sesión
|     - Enlace al portal de visitantes para que participantes accedan con su QR
|
| RUTA:       GET /  (nombre: 'home')
| CONTROLADOR: HomeController@index
|
| VARIABLES QUE RECIBE:
|   $stands → Colección de todos los stands (Stand::all()) para mostrar en las flip cards
|
| DIRECTIVAS BLADE USADAS:
|   @foreach  → Para iterar los stands y crear las tarjetas
|   @csrf     → Token de seguridad en el formulario de login del modal
|   @if/@else → Para mostrar/ocultar elementos según autenticación
|
| NO OLVIDAR:
|   - Esta vista es STANDALONE (no usa @extends), tiene su propio <html> completo
|   - Las fuentes se cargan localmente desde public/css/fonts-local.css
|   - Si se agregan más stands, las tarjetas se generan automáticamente con el @foreach
|   - El modal de login envía POST a route('login') con 'email' y 'password'
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier foire des Saveurs de la Francophonie</title>
    <link href="{{ asset('css/fonts-local.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <style>
        :root { --blue-dark: #002395; --blue-mid: #0035b5; --red-fr: #ED2939; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e8eef7 100%); min-height: 100vh; }
        .navbar-custom { background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%); box-shadow: 0 4px 20px rgba(0, 35, 149, 0.2); padding: 1rem 0; }
        .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, white, #ffd700); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-decoration: none; }
        .btn-login-nav { background: white; color: var(--blue-dark); border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-login-nav:hover { background: #f0f0f0; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 35, 149, 0.3); }
        .hero-section { padding: 60px 0; text-align: center; }
        .hero-title { font-family: 'Playfair Display', serif; font-size: 4rem; font-weight: 800; background: linear-gradient(135deg, var(--blue-dark), var(--red-fr)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px; }
        .hero-subtitle { font-size: 1.3rem; color: #555; margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto; }
        .collage-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; padding: 40px; margin: 0; }
        .flip-card { perspective: 800px; min-height: 220px; cursor: pointer; }
        .flip-card-inner { position: relative; width: 100%; height: 100%; min-height: 220px; transition: transform 0.6s cubic-bezier(.4,0,.2,1); transform-style: preserve-3d; }
        .flip-card:hover .flip-card-inner { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; -webkit-backface-visibility: hidden; border-radius: 14px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 16px; }
        .flip-card-front { background: white; box-shadow: 0 4px 15px rgba(0, 35, 149, 0.08); }
        .flip-card-back { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); color: white; transform: rotateY(180deg); box-shadow: 0 8px 25px rgba(0, 35, 149, 0.25); text-align: center; }
        .flip-card-back .back-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
        .flip-card-back .back-desc { font-size: 0.78rem; line-height: 1.5; opacity: 0.92; }
        .flip-card-back .back-origin { font-size: 0.7rem; opacity: 0.7; margin-top: 10px; font-style: italic; }
        .collage-emoji { font-size: 3.5rem; margin-bottom: 12px; color: var(--blue-dark); }
        .collage-text { font-size: 0.9rem; font-weight: 700; color: var(--blue-dark); }
        .info-section { background: white; border-radius: 16px; padding: 50px 40px; margin: 60px 0; box-shadow: 0 8px 30px rgba(0, 35, 149, 0.1); }
        .section-title { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 800; color: var(--blue-dark); margin-bottom: 30px; text-align: center; }
        .about-text { font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 40px; text-align: justify; }
        .highlights { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px; }
        .highlight-card { background: linear-gradient(135deg, rgba(0, 35, 149, 0.05), rgba(237, 41, 57, 0.05)); border: 2px solid transparent; border-radius: 12px; padding: 25px; transition: all 0.3s ease; }
        .highlight-card:hover { border-color: var(--blue-dark); transform: translateX(5px); }
        .highlight-card h4 { color: var(--blue-dark); font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .highlight-card p { color: #666; margin: 0; line-height: 1.6; }
        .stands-section { margin: 60px 0; }
        .stands-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        .stand-showcase { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 35, 149, 0.1); transition: all 0.3s ease; border: 2px solid #f0f0f0; }
        .stand-showcase:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0, 35, 149, 0.2); border-color: var(--blue-dark); }
        .stand-icon { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); color: white; font-size: 3rem; padding: 30px; text-align: center; }
        .stand-content { padding: 20px; }
        .stand-content h5 { color: var(--blue-dark); font-weight: 700; margin-bottom: 10px; }
        .stand-content p { color: #666; margin: 0; font-size: 0.9rem; }
        .cta-section { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); border-radius: 16px; padding: 60px 40px; text-align: center; margin: 60px 0; color: white; }
        .cta-title { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 800; margin-bottom: 20px; }
        .cta-subtitle { font-size: 1.1rem; margin-bottom: 30px; opacity: 0.95; }
        .btn-cta { background: white; color: var(--blue-dark); border: none; padding: 15px 40px; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; margin: 0 10px; text-decoration: none; display: inline-block; }
        .btn-cta:hover { background: #f0f0f0; transform: translateY(-3px); }
        .btn-cta-secondary { background: rgba(255,255,255,0.2); color: white; border: 2px solid white; }
        .btn-cta-secondary:hover { background: white; color: var(--blue-dark); }
        .modal-content { border: none; border-radius: 16px; }
        .modal-header { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); color: white; border: none; padding: 30px; }
        .modal-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 800; }
        .role-selector { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin: 30px 0; }
        .role-card { background: linear-gradient(135deg, rgba(0, 35, 149, 0.05), rgba(77, 126, 255, 0.05)); border: 2px solid #ddd; border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .role-card:hover { border-color: var(--blue-dark); transform: translateY(-5px); }
        .role-icon { font-size: 2.5rem; margin-bottom: 10px; }
        .role-name { font-weight: 700; color: var(--blue-dark); margin-bottom: 5px; }
        .role-desc { font-size: 0.8rem; color: #666; }
        .footer { background: #f8f9fa; border-top: 1px solid #e0e0e0; padding: 40px 0; text-align: center; color: #666; }
        @media (max-width: 768px) {
            .hero-title, .hero-section .hero-title-text { font-size: 2rem !important; }
            .hero-subtitle { font-size: 1rem; }
            .hero-section { padding: 30px 0; }
            .hero-section h2 { font-size: 1.3rem !important; }
            .role-selector { grid-template-columns: 1fr; }
            .collage-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 15px; }
            .flip-card { min-height: 180px; }
            .flip-card-inner { min-height: 180px; }
            .flip-card-front, .flip-card-back { padding: 16px 10px; }
            .collage-emoji { font-size: 2.5rem; margin-bottom: 8px; }
            .collage-text { font-size: 0.8rem; }
            .flip-card-back .back-title { font-size: 0.85rem; }
            .flip-card-back .back-desc { font-size: 0.7rem; }
            .info-section { padding: 30px 20px; margin: 30px 0; }
            .section-title { font-size: 1.5rem; }
            .about-text { font-size: 0.95rem; }
            .highlights { grid-template-columns: 1fr; gap: 15px; }
            .stands-grid { grid-template-columns: 1fr; gap: 15px; }
            .stands-section { margin: 30px 0; }
            .stand-icon { font-size: 2rem; padding: 20px; }
            .cta-section { padding: 35px 20px; margin: 30px 0; }
            .cta-title { font-size: 1.4rem; }
            .cta-subtitle { font-size: 0.95rem; }
            .btn-cta { padding: 12px 28px; font-size: 0.9rem; margin: 5px; }
            .navbar-brand-custom { font-size: 1.2rem; }
            .navbar-custom .container { padding: 0 12px; }
            .modal-header { padding: 20px; }
            .modal-title { font-size: 1.3rem; }
        }
        @media (max-width: 480px) {
            .hero-title, .hero-section .hero-title-text { font-size: 1.6rem !important; }
            .hero-section h2 { font-size: 1.1rem !important; }
            .collage-grid { padding: 10px; gap: 8px; }
            .flip-card { min-height: 160px; }
            .flip-card-inner { min-height: 160px; }
            .collage-emoji { font-size: 2rem; }
            .stands-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand navbar-brand-custom d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo-francofonia.png') }}" alt="Francofonía" style="height:48px; width:48px; margin-right:12px; vertical-align:middle; display:inline-block;">
                <span style="font-size:1.8rem; font-weight:800; color:#ffd700; font-family:'Playfair Display',serif;">Francofonía</span>
            </a>
            <button class="btn-login-nav" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </button>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center mb-4 flex-wrap">
                <img src="{{ asset('images/logo-francofonia.png') }}" alt="Francofonía" style="height:80px; width:80px; margin-right:16px;" class="hero-logo">
                <span class="hero-title hero-title-text" style="font-family:'Playfair Display',serif; color:#7a2c7a;">Francofonía</span>
            </div>
            <h2 style="font-family:'Playfair Display',serif; color:var(--blue-dark); font-size:1.8rem; margin-bottom:10px;">Premier foire des Saveurs de la Francophonie</h2>
            <p class="hero-subtitle">Primera Feria Gastronómica de la Francofonía. Una experiencia única de sabores, tradición y encuentro entre culturas francófonas.</p>
            <p style="color:#888; font-size:0.95rem;"><i class="bi bi-calendar-event"></i> 20 de marzo de 2026 &nbsp;|&nbsp; <i class="bi bi-geo-alt"></i> Salón Nakú, Universidad Tecnológica de Gutiérrez Zamora</p>
        </div>
    </section>

    <section class="collage-grid">
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-cup-hot"></i></div>
                    <div class="collage-text">Crepê</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Crepê</div>
                    <div class="back-desc">Fina masa extendida rellena de ingredientes dulces o salados. Originaria de la región de Bretaña, es uno de los platos más emblemáticos de Francia.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Bretaña, Francia</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-cake2"></i></div>
                    <div class="collage-text">Madeleine</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">La Madeleine</div>
                    <div class="back-desc">Pequeño bizcocho en forma de concha, suave y esponjoso, con sabor a mantequilla y limón. Inmortalizado por Marcel Proust en su obra literaria.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Commercy, Lorena</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-pie-chart"></i></div>
                    <div class="collage-text">Quiche Lorraine</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Quiche Lorraine</div>
                    <div class="back-desc">Tarta salada de masa quebrada rellena de huevo, crema, tocino y queso. Un clásico de la cocina francesa desde el siglo XVI.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Lorena, Francia</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-stack"></i></div>
                    <div class="collage-text">Croquenbouche</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Croquenbouche</div>
                    <div class="back-desc">Espectacular torre de profiteroles rellenos de crema y unidos con hilos de caramelo. Pieza central de bodas y celebraciones francesas.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Francia, s. XVIII</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-fire"></i></div>
                    <div class="collage-text">Crème Brûlée</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Crème Brûlée</div>
                    <div class="back-desc">Crema pastelera de vainilla cubierta con una capa crujiente de azúcar caramelizado con soplete. El postre más elegante de la gastronomía francesa.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Francia, s. XVII</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-grid-3x3"></i></div>
                    <div class="collage-text">Canapé</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Canapé</div>
                    <div class="back-desc">Elegantes bocadillos sobre pan tostado decorados con ingredientes gourmet. Imprescindibles en recepciones y cócteles de la alta cocina francesa.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Francia</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-egg-fried"></i></div>
                    <div class="collage-text">Croque Monsieur</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Croque Monsieur</div>
                    <div class="back-desc">Sándwich gratinado de jamón y queso con bechamel. El Croque Madame lleva además un huevo frito encima. Nacido en los cafés parisinos de 1910.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> París, Francia</div>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="collage-emoji"><i class="bi bi-moon"></i></div>
                    <div class="collage-text">Croissant</div>
                </div>
                <div class="flip-card-back">
                    <div class="back-title">Croissant</div>
                    <div class="back-desc">Icónico pan hojaldrado en forma de media luna, crujiente por fuera y suave por dentro. Símbolo del desayuno francés en todo el mundo.</div>
                    <div class="back-origin"><i class="bi bi-geo-alt"></i> Francia, s. XIX</div>
                </div>
            </div>
        </div>
    </section>

    <section class="info-section">
        <div class="container">
            <h2 class="section-title"><i class="bi bi-stars"></i> Sobre el Evento</h2>
            <p class="about-text">La <strong>Premier foire des Saveurs de la Francophonie</strong> es la primera feria gastronómica dentro del marco de actividades de la Francofonía, celebrada en el <strong>Salón Nakú</strong> dentro de las instalaciones de la <strong>Universidad Tecnológica de Gutiérrez Zamora</strong>, el día <strong>20 de marzo de 2026</strong>. Ven a disfrutar de los sabores auténticos de la gastronomía francesa preparados por nuestros talentosos estudiantes.</p>
            <div class="highlights">
                <div class="highlight-card">
                    <h4><i class="bi bi-shop"></i> 8 Stands Gastronómicos</h4>
                    <p>Descubre platillos clásicos de la gastronomía francesa en cada stand.</p>
                </div>
                <div class="highlight-card">
                    <h4><i class="bi bi-award"></i> Recetas Auténticas</h4>
                    <p>Cada platillo fue preparado siguiendo las recetas originales de la cocina francesa.</p>
                </div>
                <div class="highlight-card">
                    <h4><i class="bi bi-heart"></i> Experiencia Interactiva</h4>
                    <p>Participa, degusta, aprende y comparte tu opinión en nuestra encuesta.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="stands-section">
        <div class="container">
            <h2 class="section-title"><i class="bi bi-shop-window"></i> Nuestros Stands</h2>
            <div class="stands-grid">
                @foreach($stands as $stand)
                <div class="stand-showcase">
                    <div class="stand-icon"><i class="bi bi-egg-fried"></i></div>
                    <div class="stand-content">
                        <h5>{{ $stand->nombre }}</h5>
                        <p><strong>Platillo:</strong> {{ $stand->platillo }}</p>
                        <p><small><i class="bi bi-person"></i> {{ $stand->encargado }}</small></p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">¡Sé Parte de la Feria Gastronómica!</h2>
            <p class="cta-subtitle">Registra tu visita, explora los stands y comparte tu experiencia</p>
            <button class="btn-cta" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia Sesión</button>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Premier foire des Saveurs de la Francophonie &mdash; Universidad Tecnológica de Gutiérrez Zamora</p>
        </div>
    </footer>

    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Iniciar Sesión</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="modalPassword" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="modalPassword" name="password" required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleModalPassword">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3" style="font-size:1.1rem;">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('toggleModalPassword').addEventListener('click', function () {
            const pwd = document.getElementById('modalPassword');
            const icon = this.querySelector('i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'bi bi-eye-slash-fill';
            } else {
                pwd.type = 'password';
                icon.className = 'bi bi-eye-fill';
            }
        });
    </script>
    @if($errors->any() || session('openLogin'))
    <script>
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    </script>
    @endif
</body>
</html>
