@extends('layouts.app')

@section('title', 'Encuestas')
@section('page-title', 'Reportes de Encuestas')

@push('styles')
<style>
    .survey-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 28px; }
    .survey-header h1 { font-size: 1.4rem; font-weight: 700; color: #1a2340; margin: 0; }
    .survey-header p { color: #6c757d; margin: 0; font-size: .9rem; }
    .export-btns .btn { border-radius: 8px; font-weight: 600; font-size: .82rem; padding: 8px 16px; }
    .btn-excel { background: linear-gradient(135deg, #002395, #0046c8); color: #fff; border: none; }
    .btn-excel:hover { opacity: .88; color: #fff; transform: translateY(-1px); }
    .btn-pdf { background: linear-gradient(135deg, #0b3d91, #1e6dd1); color: #fff; border: none; }
    .btn-pdf:hover { opacity: .88; color: #fff; transform: translateY(-1px); }
    .score-badge { display: inline-flex; align-items: center; justify-content: center; padding: 3px 10px; border-radius: 20px; font-weight: 600; font-size: .72rem; color: #fff; white-space: nowrap; }
    .score-high { background: linear-gradient(135deg, #002395, #0046c8); }
    .score-mid  { background: linear-gradient(135deg, #1a5276, #2980b9); }
    .score-low  { background: linear-gradient(135deg, #154360, #1f78b4); }
    .avg-badge { display: inline-block; font-size: .75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; background: linear-gradient(135deg, #002395, #0046c8); color: #fff; }
    .comment-text { font-size: .8rem; color: #555; max-width: 200px; line-height: 1.4; }
    .progress-franco .progress-bar { transition: width .6s ease; }
    .card-footer .pagination { justify-content: center; margin: 0; }
    .card-footer .page-link { font-size: .78rem; padding: 4px 10px; color: #002395; }
    .card-footer .page-item.active .page-link { background: #002395; border-color: #002395; color: #fff; }
    .card-footer .page-item.disabled .page-link { color: #aaa; }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Header -->
    <div class="survey-header">
        <div>
            <h1><i class="bi bi-clipboard-data-fill" style="color:#002395;"></i> Reportes de Encuestas</h1>
            <p>Análisis de satisfacción del evento Francofonía</p>
        </div>
        <div class="export-btns d-flex gap-2">
            <a href="{{ route('surveys.export.excel') }}" class="btn btn-excel">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('surveys.export.pdf') }}" class="btn btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-blue">
                <i class="bi bi-clipboard-check stat-icon"></i>
                <div class="stat-val">{{ $totalSurveys }}</div>
                <div class="stat-label">Encuestas</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-gold">
                <i class="bi bi-people-fill stat-icon"></i>
                <div class="stat-val">{{ $totalParticipants }}</div>
                <div class="stat-label">Participantes</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-red">
                <i class="bi bi-percent stat-icon"></i>
                <div class="stat-val">
                    @if ($totalParticipants > 0)
                        {{ round(($totalSurveys / $totalParticipants) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </div>
                <div class="stat-label">Tasa de Respuesta</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-green">
                <i class="bi bi-star-fill stat-icon"></i>
                @php
                    $globalAvg = $totalSurveys > 0 ? array_sum(array_map(fn($v) => $v ?? 0, $averages)) / 4 : 0;
                @endphp
                <div class="stat-val">{{ number_format($globalAvg, 1) }}</div>
                <div class="stat-label">Promedio General</div>
            </div>
        </div>
    </div>

    <!-- Average Scores -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #002395, #0046c8); color: #fff; border-radius: 14px 14px 0 0 !important;">
            <h5 class="mb-0" style="font-weight:700;"><i class="bi bi-bar-chart-line"></i> Calificaciones Promedio</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($questions as $key => $question)
                    @php
                        $avg = $averages[$key] ?? 0;
                        $percentage = ($avg / 10) * 100;
                        if ($percentage >= 80) $barColor = '#002395';
                        elseif ($percentage >= 60) $barColor = '#1a5276';
                        else $barColor = '#154360';
                    @endphp
                    <div class="col-md-6 mb-4">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <strong style="font-size:.88rem;">{{ $question }}</strong>
                            <span class="avg-badge">{{ number_format($avg, 2) }}/10</span>
                        </div>
                        <div class="progress progress-franco" style="height: 22px; border-radius: 8px; background: #eef0f9;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $percentage }}%; background: {{ $barColor }}; border-radius: 8px; font-size:.75rem; font-weight:600;"
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($percentage, 0) }}%
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Survey Details Table -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #002395, #0046c8); color: #fff; border-radius: 14px 14px 0 0 !important;">
            <h5 class="mb-0" style="font-weight:700;"><i class="bi bi-table"></i> Detalle de Encuestas</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-modern table-hover mb-0">
                <thead>
                    <tr>
                        <th>Participante</th>
                        <th class="text-center">Presentación</th>
                        <th class="text-center">Atención</th>
                        <th class="text-center">Satisfacción</th>
                        <th class="text-center">Recomendación</th>
                        <th class="text-center">Nivel</th>
                        <th>Comentarios</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $survey)
                        @php
                            $scoreClass = fn($v) => $v >= 8 ? 'score-high' : ($v >= 5 ? 'score-mid' : 'score-low');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $survey->participant->nombre }} {{ $survey->participant->paterno }}</strong>
                                <br>
                                <small class="text-muted">{{ $survey->participant->correo }}</small>
                            </td>
                            <td class="text-center"><span class="score-badge {{ $scoreClass($survey->q1) }}">{{ $survey->q1 }}/10</span></td>
                            <td class="text-center"><span class="score-badge {{ $scoreClass($survey->q2) }}">{{ $survey->q2 }}/10</span></td>
                            <td class="text-center"><span class="score-badge {{ $scoreClass($survey->q3) }}">{{ $survey->q3 }}/10</span></td>
                            <td class="text-center"><span class="score-badge {{ $scoreClass($survey->q4) }}">{{ $survey->q4 }}/10</span></td>
                            <td class="text-center">
                                <span class="avg-badge">{{ $survey->getSatisfactionLevel() }}</span>
                            </td>
                            <td>
                                @if($survey->comentarios)
                                    <div class="comment-text">{{ Str::limit($survey->comentarios, 80) }}</div>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $survey->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:1.5rem;"></i><br>
                                No hay encuestas registradas aún
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($surveys->hasPages())
            <div class="card-footer bg-light d-flex justify-content-center py-2">
                {{ $surveys->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Comentarios Section -->
    <div class="card mt-4">
        <div class="card-header" style="background: linear-gradient(135deg, #002395, #0046c8); color: #fff; border-radius: 14px 14px 0 0 !important;">
            <h5 class="mb-0" style="font-weight:700;"><i class="bi bi-chat-square-text"></i> Comentarios de Participantes</h5>
        </div>
        <div class="card-body">
            @if($comments->count() > 0)
                <div class="row g-3">
                    @foreach($comments as $comment)
                        <div class="col-md-6">
                            <div style="background: #f5f7fc; border-radius: 12px; padding: 18px; border-left: 4px solid #002395; height: 100%;">
                                <div style="font-size: .92rem; color: #333; line-height: 1.6; margin-bottom: 10px;">
                                    "{{ $comment->comentarios }}"
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small style="color: #8c92a8;"><i class="bi bi-person-circle"></i> Anónimo</small>
                                    <small style="color: #8c92a8;"><i class="bi bi-clock"></i> {{ $comment->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-chat-square" style="font-size:1.5rem;"></i><br>
                    No hay comentarios aún
                </div>
            @endif
        </div>
        @if ($comments->hasPages())
            <div class="card-footer bg-light d-flex justify-content-center py-2">
                {{ $comments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('reports.index') }}" class="btn btn-franco">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>
</div>
@endsection
