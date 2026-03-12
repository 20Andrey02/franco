@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="h2 mb-3">
                <i class="bi bi-graph-up"></i> Reportes de Encuestas
            </h1>
            <p class="text-muted">Análisis de satisfacción del evento Francofonía</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('surveys.export.excel') }}" class="btn btn-success btn-sm me-2">
                <i class="bi bi-file-earmark-excel"></i> Descargar Excel
            </a>
            <a href="{{ route('surveys.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total de Encuestas</h6>
                    <h3 class="mb-0">{{ $totalSurveys }}</h3>
                    <small class="text-muted">de {{ $totalParticipants }} participantes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title text-muted">Tasa de Respuesta</h6>
                    <h3 class="mb-0">
                        @if ($totalParticipants > 0)
                            {{ round(($totalSurveys / $totalParticipants) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Scores -->
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📊 Calificaciones Promedio</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($questions as $key => $question)
                    @php
                        $avg = $averages[$key] ?? 0;
                        $percentage = ($avg / 5) * 100;
                    @endphp
                    <div class="col-md-6 mb-4">
                        <div class="mb-2">
                            <strong>{{ $question }}</strong>
                            <span class="badge bg-primary float-end">{{ number_format($avg, 2) }}/5</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: 
                                @if ($percentage >= 80)
                                    #198754
                                @elseif ($percentage >= 60)
                                    #ffc107
                                @else
                                    #dc3545
                                @endif
                            " aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
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
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📋 Detalle de Encuestas</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Participante</th>
                        <th class="text-center">P1</th>
                        <th class="text-center">P2</th>
                        <th class="text-center">P3</th>
                        <th class="text-center">P4</th>
                        <th class="text-center">P5</th>
                        <th class="text-center">Promedio</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $survey)
                        @php
                            $avgScore = ($survey->q1 + $survey->q2 + $survey->q3 + $survey->q4 + $survey->q5) / 5;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $survey->participant->nombre }} {{ $survey->participant->paterno }}</strong>
                                <br>
                                <small class="text-muted">{{ $survey->participant->correo }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $survey->q1 >= 4 ? 'success' : ($survey->q1 >= 3 ? 'warning' : 'danger') }}">
                                    {{ $survey->q1 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $survey->q2 >= 4 ? 'success' : ($survey->q2 >= 3 ? 'warning' : 'danger') }}">
                                    {{ $survey->q2 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $survey->q3 >= 4 ? 'success' : ($survey->q3 >= 3 ? 'warning' : 'danger') }}">
                                    {{ $survey->q3 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $survey->q4 >= 4 ? 'success' : ($survey->q4 >= 3 ? 'warning' : 'danger') }}">
                                    {{ $survey->q4 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $survey->q5 >= 4 ? 'success' : ($survey->q5 >= 3 ? 'warning' : 'danger') }}">
                                    {{ $survey->q5 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($avgScore, 2) }}</strong>
                            </td>
                            <td class="text-muted small">
                                {{ $survey->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay encuestas registradas aún
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($surveys->hasPages())
            <div class="card-footer bg-light">
                {{ $surveys->links() }}
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>
</div>
@endsection
