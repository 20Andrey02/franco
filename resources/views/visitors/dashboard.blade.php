<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel — Francofonía</title>

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

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef7 100%);
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
            box-shadow: 0 4px 20px rgba(0, 35, 149, 0.15);
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
        }

        .participant-welcome {
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 35, 149, 0.1);
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .profile-info h2 {
            margin: 0;
            color: var(--blue-dark);
            font-size: 1.5rem;
        }

        .profile-info p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 0.95rem;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-box {
            background: linear-gradient(135deg, rgba(0, 35, 149, 0.05) 0%, rgba(77, 126, 255, 0.05) 100%);
            border: 1px solid rgba(0, 35, 149, 0.1);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }

        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--blue-dark);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }

        /* Section */
        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--blue-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stands Grid */
        .stands-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .stand-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 35, 149, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .stand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 35, 149, 0.15);
        }

        .stand-card.visited {
            border-color: #4dbaff;
            background: linear-gradient(135deg, rgba(77, 186, 255, 0.02) 0%, rgba(77, 126, 255, 0.02) 100%);
        }

        .stand-header {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: white;
            padding: 20px;
            position: relative;
        }

        .stand-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .visited-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #4dbaff;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .stand-body {
            padding: 20px;
        }

        .stand-detail {
            margin-bottom: 12px;
        }

        .stand-detail-label {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stand-detail-value {
            font-size: 1rem;
            color: #333;
            margin-top: 4px;
        }

        .stand-detail-icon {
            width: 24px;
            display: inline-block;
        }

        /* Visits Timeline */
        .visits-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 35, 149, 0.08);
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item {
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -32px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--blue-dark);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--blue-dark);
        }

        .timeline-time {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 3px;
        }

        .timeline-stand {
            font-weight: 600;
            color: var(--blue-dark);
        }

        /* Survey CTA */
        .survey-cta {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 35, 149, 0.2);
        }

        .survey-cta h3 {
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .survey-cta p {
            margin: 0 0 20px 0;
            opacity: 0.95;
        }

        .btn-survey {
            background: white;
            color: var(--blue-dark);
            border: none;
            border-radius: 10px;
            padding: 12px 32px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-survey:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }

        .survey-badge {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .stands-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .profile-card {
                padding: 20px;
            }

            .profile-info h2 {
                font-size: 1.2rem;
            }

            .navbar-brand-custom {
                font-size: 1.2rem;
            }

            .survey-cta {
                padding: 20px;
            }

            .survey-cta h3 {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .container.py-5 {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }

        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-number {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand-custom" href="{{ route('home') }}">
                <i class="bi bi-person-circle"></i> Francofonía
            </a>
            <div class="ms-auto">
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-franco" style="display:inline-flex; align-items:center; gap:8px; border-radius:8px; padding:9px 20px; font-weight:700; color:#1a2340; box-shadow:none; border:1px solid #d1d7e8; background:white;">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Profile Section -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    @if (strtoupper($participant->sexo) == 'M')
                        <i class="bi bi-person-fill"></i>
                    @elseif (strtoupper($participant->sexo) == 'F')
                        <i class="bi bi-person-fill"></i>
                    @else
                        <i class="bi bi-person-raised-hand"></i>
                    @endif
                </div>
                <div class="profile-info">
                    <h2>{{ $participant->nombre }} {{ $participant->paterno }}</h2>
                    <p><i class="bi bi-envelope"></i> {{ $participant->correo }}</p>
                    <p><i class="bi bi-geo-alt"></i> {{ $participant->ciudad }} @if($participant->municipio), {{ $participant->municipio }} @endif</p>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-icon"><i class="bi bi-ticket-perforated"></i></div>
                    <div class="stat-number">{{ $totalVisits }}</div>
                    <div class="stat-label">Visitas Realizadas</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="bi bi-shop"></i></div>
                    <div class="stat-number">{{ $standsInfo->filter(fn($s) => $s['visited'])->count() }}</div>
                    <div class="stat-label">Stands Visitados</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="bi bi-pencil-square"></i></div>
                    <div class="stat-number">
                        @if ($survey)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        @endif
                    </div>
                    <div class="stat-label">Encuesta</div>
                </div>
            </div>
        </div>

        <!-- Stands Section -->
        <div class="section">
            <h2 class="section-title">
                <i class="bi bi-shop"></i> Stands Disponibles
            </h2>
            <div class="stands-grid">
                @foreach ($standsInfo as $stand)
                    <div class="stand-card @if($stand['visited']) visited @endif">
                        <div class="stand-header">
                            {{ $stand['nombre'] }}
                            @if ($stand['visited'])
                                <span class="visited-badge"><i class="bi bi-check-circle-fill"></i> ¡Visitado!</span>
                            @endif
                        </div>
                        <div class="stand-body">
                            <div class="stand-detail">
                                <div class="stand-detail-label"><i class="bi bi-egg-fried"></i> Platillo</div>
                                <div class="stand-detail-value">{{ $stand['platillo'] }}</div>
                            </div>
                            <div class="stand-detail">
                                <div class="stand-detail-label"><i class="bi bi-journal-text"></i> Descripción</div>
                                <div class="stand-detail-value">{{ $stand['descripcion'] }}</div>
                            </div>
                            <div class="stand-detail">
                                <div class="stand-detail-label"><i class="bi bi-person"></i> Encargado</div>
                                <div class="stand-detail-value">{{ $stand['encargado'] }}</div>
                            </div>
                            @if ($stand['visited'] && $stand['last_visit'])
                                <div class="stand-detail">
                                    <div class="stand-detail-label"><i class="bi bi-clock"></i> Última visita</div>
                                    <div class="stand-detail-value">{{ $stand['last_visit']->format('H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Visits Section -->
        @if ($visits->count() > 0)
            <div class="section">
                <h2 class="section-title">
                    <i class="bi bi-clock-history"></i> Historial de Visitas ({{ $visits->count() }})
                </h2>
                <div class="visits-container">
                    <div class="timeline">
                        @foreach ($visits as $visit)
                            <div class="timeline-item">
                                <div class="timeline-time">{{ $visit->visit_time->format('d/m/Y H:i') }}</div>
                                <div class="timeline-stand">
                                    <i class="bi bi-check-circle-fill"></i> 
                                    {{ $visit->stand->nombre }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="section">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Aún no has visitado ningún stand.</strong>
                    ¡Dirígete a los stands del evento para que el personal escanee tu código QR!
                </div>
            </div>
        @endif

        <!-- Survey CTA -->
        <div class="section">
            @if ($survey)
                <div class="survey-cta" style="background: linear-gradient(135deg, #4dbaff, #28a745);">
                    <h3><i class="bi bi-check-circle"></i> Encuesta Completada</h3>
                    <p>¡Gracias por tu feedback! Tu opinión es valiosa para nosotros.</p>
                    <a href="{{ route('visitors.dashboard', ['code' => $participant->qr_code]) }}" class="btn-survey" style="background: white; color: #087e8b;">
                        <i class="bi bi-arrow-clockwise"></i> Refrescar
                    </a>
                </div>
            @else
                <div class="survey-cta">
                    <h3><i class="bi bi-clipboard-check"></i> ¡Tu Opinión Importa!</h3>
                    <p>Completa una breve encuesta de satisfacción sobre tu experiencia en el evento. ¡Solo toma 2 minutos!</p>
                    <a href="{{ route('survey.show', ['code' => $participant->qr_code]) }}" class="btn-survey">
                        <i class="bi bi-arrow-right"></i> Responder Encuesta
                    </a>
                    <div class="survey-badge">
                        <i class="bi bi-star-fill"></i> Valoramos tu feedback
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
