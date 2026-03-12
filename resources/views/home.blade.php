<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Francofonía - Evento Cultural</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
        .collage-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; padding: 40px; margin: 0; }
        .collage-item { background: white; border-radius: 12px; padding: 30px 20px; text-align: center; box-shadow: 0 4px 15px rgba(0, 35, 149, 0.08); transition: all 0.3s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 180px; }
        .collage-item:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0, 35, 149, 0.15); }
        .collage-emoji { font-size: 3.5rem; margin-bottom: 12px; }
        .collage-text { font-size: 0.85rem; font-weight: 600; color: var(--blue-dark); }
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
        @media (max-width: 768px) { .hero-title { font-size: 2.5rem; } .role-selector { grid-template-columns: 1fr; } }
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
            <div class="d-flex justify-content-center align-items-center mb-4">
                <img src="{{ asset('images/logo-francofonia.png') }}" alt="Francofonía" style="height:80px; width:80px; margin-right:24px;">
                <span class="hero-title" style="font-size:4rem; font-family:'Playfair Display',serif; color:#7a2c7a;">Francofonía</span>
            </div>
            <p class="hero-subtitle">Celebra la riqueza cultural de la Francofonía. Una experiencia única de gastronomía, tradición y encuentro entre culturas francófonas.</p>
        </div>
    </section>

    <section class="collage-grid">
        <div class="collage-item"><div class="collage-emoji">🥐</div><div class="collage-text">Croissants</div></div>
        <div class="collage-item"><div class="collage-emoji">🇫🇷</div><div class="collage-text">Francia</div></div>
        <div class="collage-item"><div class="collage-emoji">🧇</div><div class="collage-text">Waffles</div></div>
        <div class="collage-item"><div class="collage-emoji">🍽️</div><div class="collage-text">Gastronomía</div></div>
        <div class="collage-item"><div class="collage-emoji">🎵</div><div class="collage-text">Música</div></div>
        <div class="collage-item"><div class="collage-emoji">🎨</div><div class="collage-text">Arte</div></div>
        <div class="collage-item"><div class="collage-emoji">🗼</div><div class="collage-text">Cultura</div></div>
        <div class="collage-item"><div class="collage-emoji">🍷</div><div class="collage-text">Vinos</div></div>
    </section>

    <section class="info-section">
        <div class="container">
            <h2 class="section-title">✨ Sobre el Evento</h2>
            <p class="about-text">Francofonía es un vibrante evento cultural que celebra la diversidad y riqueza del mundo francófono. Durante esta experiencia única, explora diferentes stands temáticos donde conocerás la gastronomía, el arte, la música y las tradiciones de países francófonos.</p>
            <div class="highlights">
                <div class="highlight-card">
                    <h4><i class="bi bi-shop"></i> Múltiples Stands</h4>
                    <p>Descubre experiencias culinarias y culturales en cada punto del evento.</p>
                </div>
                <div class="highlight-card">
                    <h4><i class="bi bi-award"></i> Tradiciones Auténticas</h4>
                    <p>Vive la autenticidad de las tradiciones francófonas con expertos.</p>
                </div>
                <div class="highlight-card">
                    <h4><i class="bi bi-heart"></i> Experiencia Interactiva</h4>
                    <p>Participa, degusta, aprende y comparte con otros visitantes.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="stands-section">
        <div class="container">
            <h2 class="section-title">🎪 Stands Disponibles</h2>
            <div class="stands-grid">
                <div class="stand-showcase">
                    <div class="stand-icon">🥐</div>
                    <div class="stand-content">
                        <h5>Stand Francia</h5>
                        <p>Croissants recién horneados y especialidades francesas auténticas.</p>
                    </div>
                </div>
                <div class="stand-showcase">
                    <div class="stand-icon">🧇</div>
                    <div class="stand-content">
                        <h5>Stand Bélgica</h5>
                        <p>Waffles belgas tradicionales con toppings gourmet.</p>
                    </div>
                </div>
                <div class="stand-showcase">
                    <div class="stand-icon">🧀</div>
                    <div class="stand-content">
                        <h5>Stand Suiza</h5>
                        <p>Fondue de queso suizo y especialidades alpinas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">¡Sé Parte de Francofonía!</h2>
            <p class="cta-subtitle">Registra tu visita, explora los stands y comparte tu experiencia</p>
            <button class="btn-cta" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia Sesión</button>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Francofonía. Celebrando la Diversidad Cultural.</p>
        </div>
    </footer>

    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @if($errors->any())
    <script>
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    </script>
    @endif
</body>
</html>
