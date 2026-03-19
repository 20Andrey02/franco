<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gafete — {{ $participant->nombre }} {{ $participant->paterno }}</title>
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fonts-local.css') }}" rel="stylesheet">
    <style>

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .badge-card {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 360px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        }

        .badge-header {
            background: linear-gradient(135deg, #002395, #1a4de8);
            color: #fff;
            text-align: center;
            padding: 24px 20px 18px;
        }

        .badge-header img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 50%;
            margin-bottom: 10px;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        }

        .badge-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 800;
            margin: 0;
        }

        .badge-header small {
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.7;
        }

        .flag-bar {
            display: flex;
            height: 4px;
        }
        .f-blue  { flex: 1; background: #002395; }
        .f-white { flex: 1; background: #fff; }
        .f-red   { flex: 1; background: #ED2939; }

        .badge-body {
            padding: 24px 20px;
            text-align: center;
        }

        .participant-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .participant-email {
            font-size: 0.82rem;
            color: #666;
            margin-bottom: 20px;
        }

        .qr-container {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            display: inline-block;
            margin-bottom: 16px;
        }

        .qr-container svg {
            display: block;
            margin: 0 auto;
        }

        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #002395;
            font-weight: 700;
            margin-top: 10px;
        }

        .qr-hint {
            font-size: 0.72rem;
            color: #999;
            margin-top: 6px;
        }

        .badge-footer {
            background: #f8f9fa;
            padding: 14px 20px;
            text-align: center;
            font-size: 0.75rem;
            color: #999;
            border-top: 1px solid #eee;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-print {
            background: linear-gradient(135deg, #002395, #1a4de8);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back {
            background: #e9ecef;
            color: #333;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none !important; }
            .badge-card { box-shadow: none; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="badge-card">
    <div class="badge-header">
        <img src="{{ asset('images/logo-francofonia.png') }}" alt="Logo">
        <h1>Francofonía</h1>
        <small>Sistema de Estands</small>
    </div>

    <div class="flag-bar">
        <div class="f-blue"></div>
        <div class="f-white"></div>
        <div class="f-red"></div>
    </div>

    <div class="badge-body">
        <p class="participant-name">{{ $participant->nombre }} {{ $participant->paterno }} {{ $participant->materno }}</p>
        <p class="participant-email">{{ $loginEmail }}</p>

        @if($participant->qr_code)
        <div class="qr-container">
            {!! QrCode::size(180)->errorCorrection('H')->generate($qrUrl) !!}
            <p class="qr-code-text">{{ $participant->qr_code }}</p>
            <p class="qr-hint">Escanear en cada estand</p>
        </div>
        @endif
    </div>

    <div class="badge-footer">
        <strong>Accede a tu dashboard:</strong><br>
        Correo: <strong>{{ $loginEmail }}</strong><br>
        Contraseña: <strong>{{ $participant->qr_code }}</strong>
    </div>
</div>

<div class="actions">
    <a href="{{ route('participants.badge.pdf', $participant) }}" class="btn-print">
        <i class="bi bi-file-earmark-pdf-fill"></i> Descargar PDF
    </a>
    <button class="btn-print" onclick="window.print()">
        <i class="bi bi-printer-fill"></i> Imprimir
    </button>
    <a href="{{ route('participants.show', $participant) }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

</body>
</html>
