<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Visitantes — Francofonía</title>

    <!-- Local Fonts -->
    <link href="{{ asset('css/fonts-local.css') }}" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --blue-dark: #002395;
            --blue-mid:  #0035b5;
            --red-fr:    #ED2939;
            --gold:      #d4af37;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--blue-dark) 0%, #1a4de8 50%, #0d2b7a 100%);
            overflow-x: hidden;
        }

        .bg-animated {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(237, 41, 57, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(212, 175, 55, 0.15) 0%, transparent 50%);
        }

        .bg-animated::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(255,255,255,0.02) 2px,
                rgba(255,255,255,0.02) 4px
            );
        }

        .container-fluid {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header-visitors {
            background: rgba(0, 35, 149, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px 0;
            border-bottom: 3px solid var(--gold);
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 20px;
            justify-content: center;
        }

        .logo-flag {
            width: 6px;
            height: 40px;
            border-radius: 3px;
            background: linear-gradient(to bottom, #002395 33%, #fff 33% 66%, #ED2939 66%);
        }

        .logo-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .logo-text p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            margin-top: -5px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .card-login {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 35, 149, 0.3);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .card-header-custom h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            margin-bottom: 10px;
        }

        .card-header-custom p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .card-body-custom {
            padding: 40px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--blue-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--blue-dark);
            box-shadow: 0 0 0 3px rgba(0, 35, 149, 0.1);
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group-text {
            background: var(--blue-dark);
            color: white;
            border: 2px solid var(--blue-dark);
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
            border: none;
            border-radius: 10px;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(0, 35, 149, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 35, 149, 0.4);
            background: linear-gradient(135deg, #0d2b7a 0%, #001a70 100%);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            gap: 10px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider-text {
            color: #999;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .info-card {
            background: linear-gradient(135deg, rgba(0, 35, 149, 0.1) 0%, rgba(77, 126, 255, 0.05) 100%);
            border: 1px solid rgba(0, 35, 149, 0.2);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }

        .info-card-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .info-card-text {
            font-size: 0.8rem;
            color: #555;
            font-weight: 500;
        }

        .alerts-container {
            margin-bottom: 20px;
        }

        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .alert-danger {
            background: #ffebee;
            color: #c62828;
        }

        .alert-info {
            background: #e3f2fd;
            color: #1565c0;
        }

        /* Footer */
        .footer-visitors {
            background: rgba(0, 35, 149, 0.95);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .logo-text h1 {
                font-size: 1.8rem;
            }

            .card-body-custom {
                padding: 25px;
            }

            .card-header-custom h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animated"></div>

    <div class="container-fluid">
        <!-- Header -->
        <div class="header-visitors">
            <div class="logo-section">
                <div class="logo-flag"></div>
                <div class="logo-text">
                    <h1>Francofonía</h1>
                    <p><i class="bi bi-mask"></i> Portal de Visitantes</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="card-login">
                <div class="card-header-custom">
                    <h2>Bienvenido</h2>
                    <p>Ingresa con tu código QR para acceder al evento</p>
                </div>

                <div class="card-body-custom">
                    @if(session('error'))
                        <div class="alerts-container">
                            <div class="alert-custom alert-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alerts-container">
                            <div class="alert-custom alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="section-title">
                        <i class="bi bi-qr-code"></i>
                        Acceso por QR
                    </div>

                    <!-- QR Camera Scanner -->
                    <div id="qr-scanner-section" style="margin-bottom: 20px;">
                        <button type="button" id="btnToggleCamera" class="btn-primary-custom" style="margin-bottom: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="bi bi-camera"></i> Escanear con Cámara
                        </button>
                        <div id="qr-reader" style="display: none; width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 15px;"></div>
                        <div id="qr-status" style="display: none; text-align: center; padding: 10px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 10px;"></div>
                    </div>

                    <div class="divider" style="margin: 15px 0;">
                        <div class="divider-line"></div>
                        <span class="divider-text">o ingresa manualmente</span>
                        <div class="divider-line"></div>
                    </div>

                    <form action="{{ route('visitors.dashboard') }}" method="GET" id="qrForm">
                        <div class="form-group">
                            <label for="code" class="form-label">Código Manual</label>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    class="form-control"
                                    id="code"
                                    name="code"
                                    placeholder="Ingresa: FRANCO-000001"
                                    required
                                >
                                <button class="input-group-text" type="submit">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="divider">
                        <div class="divider-line"></div>
                        <span class="divider-text">Información del Evento</span>
                        <div class="divider-line"></div>
                    </div>

                    <div class="info-cards">
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-shop"></i></div>
                            <div class="info-card-text">{{ $stands->count() }} Stands Disponibles</div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-globe2"></i></div>
                            <div class="info-card-text">Culturas Francófonas</div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-star"></i></div>
                            <div class="info-card-text">Experiencia Única</div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-bar-chart-line"></i></div>
                            <div class="info-card-text">Encuesta Incluida</div>
                        </div>
                    </div>

                    <div class="alert alert-custom alert-info" style="margin-top: 25px;">
                        <i class="bi bi-info-circle"></i>
                        <span><strong><i class="bi bi-lightbulb"></i> Tip:</strong> Una vez ingresan, podrán ver todos los stands, sus visitas y responder la encuesta de satisfacción.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-visitors">
            <p class="mb-0">
                &copy; {{ date('Y') }} Francofonía — Evento Cultural &nbsp;|&nbsp; 
                <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">Volver al inicio</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script>
        (function() {
            const btnToggle = document.getElementById('btnToggleCamera');
            const qrReaderDiv = document.getElementById('qr-reader');
            const qrStatus = document.getElementById('qr-status');
            const codeInput = document.getElementById('code');
            const qrForm = document.getElementById('qrForm');
            let html5QrCode = null;
            let scanning = false;

            function showStatus(msg, type) {
                qrStatus.style.display = 'block';
                qrStatus.textContent = msg;
                qrStatus.style.background = type === 'success' ? '#e8f5e9' : type === 'error' ? '#ffebee' : '#e3f2fd';
                qrStatus.style.color = type === 'success' ? '#2e7d32' : type === 'error' ? '#c62828' : '#1565c0';
            }

            function stopScanner() {
                if (html5QrCode && scanning) {
                    html5QrCode.stop().then(function() {
                        scanning = false;
                        qrReaderDiv.style.display = 'none';
                        btnToggle.innerHTML = '<i class="bi bi-camera"></i> Escanear con Cámara';
                    }).catch(function() {
                        scanning = false;
                    });
                }
            }

            function onScanSuccess(decodedText) {
                stopScanner();
                showStatus('Código detectado: ' + decodedText, 'success');
                codeInput.value = decodedText;
                setTimeout(function() {
                    qrForm.submit();
                }, 500);
            }

            btnToggle.addEventListener('click', function() {
                if (scanning) {
                    stopScanner();
                    qrStatus.style.display = 'none';
                    return;
                }

                qrReaderDiv.style.display = 'block';
                btnToggle.innerHTML = '<i class="bi bi-camera-video-off"></i> Detener Cámara';

                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }

                // La cámara requiere HTTPS o localhost en navegadores móviles
                if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                    qrReaderDiv.style.display = 'none';
                    btnToggle.innerHTML = '<i class="bi bi-camera"></i> Escanear con Cámara';
                    showStatus('⚠️ La cámara requiere conexión HTTPS. Por favor ingresa tu código manualmente abajo.', 'error');
                    return;
                }

                showStatus('Iniciando cámara...', 'info');

                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess
                ).then(function() {
                    scanning = true;
                    showStatus('Apunta la cámara al código QR', 'info');
                }).catch(function(err) {
                    qrReaderDiv.style.display = 'none';
                    btnToggle.innerHTML = '<i class="bi bi-camera"></i> Escanear con Cámara';
                    if (err.toString().includes('NotAllowedError') || err.toString().includes('Permission')) {
                        showStatus('Permiso de cámara denegado. Permite el acceso en la configuración de tu navegador.', 'error');
                    } else if (err.toString().includes('NotFoundError')) {
                        showStatus('No se encontró cámara en este dispositivo.', 'error');
                    } else if (err.toString().includes('NotReadableError') || err.toString().includes('Could not start')) {
                        showStatus('La cámara está en uso por otra aplicación. Ciérrala e intenta de nuevo.', 'error');
                    } else {
                        showStatus('No se pudo acceder a la cámara. Intenta usar el código manual.', 'error');
                    }
                });
            });
        })();
    </script>
</body>
</html>
