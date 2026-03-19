<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Encuestas - Francofonía</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #002395;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #002395;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-box {
            text-align: center;
            flex: 1;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #002395;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .section-title {
            background: #002395;
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            font-size: 16px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th {
            background: #0035b5;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #002395;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .progress-bar {
            background: #e0e0e0;
            border-radius: 3px;
            height: 20px;
            position: relative;
            overflow: hidden;
        }
        .progress-fill {
            background: #4dbaff;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        .rating-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .rating-excellent { background: #28a745; }
        .rating-good { background: #4dbaff; }
        .rating-fair { background: #ffc107; }
        .rating-poor { background: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="bi bi-clipboard-check"></i> Reportes de Encuestas — Sabores de la Francofonía</h1>
        <p>Análisis de Satisfacción del Evento</p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $totalSurveys }}</div>
            <div class="stat-label">Encuestas Respondidas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $totalParticipants }}</div>
            <div class="stat-label">Total Participantes</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $totalParticipants > 0 ? round(($totalSurveys / $totalParticipants) * 100, 1) : 0 }}%</div>
            <div class="stat-label">Tasa de Respuesta</div>
        </div>
    </div>

    <div class="section-title"><i class="bi bi-bar-chart-line"></i> Calificaciones Promedio</div>
    <table>
        <thead>
            <tr>
                <th>Pregunta</th>
                <th>Calificación Promedio</th>
                <th>Distribución</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
                $colors = [
                    'q1' => '#4dbaff',
                    'q2' => '#28a745',
                    'q3' => '#ffc107',
                    'q4' => '#ed7d31',
                ];
            @endphp
            @foreach ($questions as $key => $question)
                @php
                    $avg = $averages[$key] ?? 0;
                    $percentage = ($avg / 10) * 100;
                @endphp
                <tr>
                    <td>{{ $question }}</td>
                    <td><strong>{{ number_format($avg, 2) }}/10</strong></td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $percentage }}%; background: {{ $colors[$key] }};">
                                {{ round($percentage, 0) }}%
                            </div>
                        </div>
                    </td>
                    <td>{{ round($percentage, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title"><i class="bi bi-pencil-square"></i> Detalle de Encuestas</div>
    <table>
        <thead>
            <tr>
                <th>Participante</th>
                <th>Email</th>
                <th>P1</th>
                <th>P2</th>
                <th>P3</th>
                <th>P4</th>
                <th>Promedio</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($surveys as $survey)
                @php
                    $avgScore = ($survey->q1 + $survey->q2 + $survey->q3 + $survey->q4) / 4;
                    if ($avgScore >= 9) {
                        $badgeClass = 'rating-excellent';
                    } elseif ($avgScore >= 7) {
                        $badgeClass = 'rating-good';
                    } elseif ($avgScore >= 5) {
                        $badgeClass = 'rating-fair';
                    } else {
                        $badgeClass = 'rating-poor';
                    }
                @endphp
                <tr>
                    <td>{{ $survey->participant->nombre }} {{ $survey->participant->paterno }}</td>
                    <td>{{ $survey->participant->correo }}</td>
                    <td>{{ $survey->q1 }}</td>
                    <td>{{ $survey->q2 }}</td>
                    <td>{{ $survey->q3 }}</td>
                    <td>{{ $survey->q4 }}</td>
                    <td>
                        <span class="rating-badge {{ $badgeClass }}">
                            {{ number_format($avgScore, 2) }}
                        </span>
                    </td>
                    <td>{{ $survey->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">No hay encuestas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Sabores de la Francofonía — UTGZ | Sistema de Reportes de Satisfacción</p>
    </div>
</body>
</html>
