<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background: #fff;
            display: flex;
            justify-content: center;
        }

        .badge-card {
            width: 320px;
            border: 2px solid #002395;
            border-radius: 16px;
            overflow: hidden;
            margin: 0 auto;
        }

        .badge-header {
            background: #002395;
            color: #fff;
            text-align: center;
            padding: 20px 16px 14px;
        }

        .badge-header h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 8px 0 2px;
        }

        .badge-header small {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.7;
        }

        .flag-bar {
            height: 4px;
            display: flex;
        }
        .flag-bar .f-blue  { flex: 1; background: #002395; }
        .flag-bar .f-white { flex: 1; background: #fff; }
        .flag-bar .f-red   { flex: 1; background: #ED2939; }

        .badge-body {
            padding: 24px 16px;
            text-align: center;
        }

        .participant-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 4px;
        }

        .participant-email {
            font-size: 11px;
            color: #666;
            margin: 0 0 20px;
        }

        .qr-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            display: inline-block;
        }

        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #002395;
            font-weight: 700;
            margin: 10px 0 0;
        }

        .qr-hint {
            font-size: 10px;
            color: #999;
            margin: 4px 0 0;
        }

        .badge-footer {
            background: #f8f9fa;
            padding: 10px 16px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
        }

        table.flag-bar-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.flag-bar-table td {
            height: 4px;
            width: 33.33%;
        }
    </style>
</head>
<body>

<div class="badge-card">
    <div class="badge-header">
        <h1>Francofonía</h1>
        <small>Sistema de Estands</small>
    </div>

    <table class="flag-bar-table">
        <tr>
            <td style="background:#002395;"></td>
            <td style="background:#ffffff;"></td>
            <td style="background:#ED2939;"></td>
        </tr>
    </table>

    <div class="badge-body">
        <p class="participant-name">{{ $participant->nombre }} {{ $participant->paterno }} {{ $participant->materno }}</p>
        <p class="participant-email">{{ $loginEmail }}</p>

        @if($participant->qr_code)
        <div class="qr-container">
            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" width="180" height="180">
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

</body>
</html>
