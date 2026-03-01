@extends('layouts.app')

@section('title', 'Reporte de visitas')
@section('page-title', 'Reporte de visitas por estand')

@section('content')
{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-blue">
            <i class="bi bi-people-fill stat-icon"></i>
            <div class="stat-val">{{ $totalParticipants }}</div>
            <div class="stat-label">Participantes registrados</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-green">
            <i class="bi bi-person-check-fill stat-icon"></i>
            <div class="stat-val">{{ $activeParticipants }}</div>
            <div class="stat-label">Participantes activos (con visitas)</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-gold">
            <i class="bi bi-eye-fill stat-icon"></i>
            <div class="stat-val">{{ $totalVisits }}</div>
            <div class="stat-label">Visitas totales</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-red">
            <i class="bi bi-grid-3x3-gap-fill stat-icon"></i>
            <div class="stat-val">{{ $stands->count() }}</div>
            <div class="stat-label">Estands participantes</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Ranking de stands --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy-fill me-2" style="color:var(--gold)"></i>Ranking de estands por visitas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Pos.</th>
                                <th>Estand</th>
                                <th>Platillo</th>
                                <th>Encargado</th>
                                <th>Visitas</th>
                                <th>Barra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxVisits = $stands->max('visits_count') ?: 1; @endphp
                            @forelse($stands as $idx => $stand)
                            <tr>
                                <td>
                                    @if($idx === 0)
                                        <i class="bi bi-trophy-fill" style="color:#d4af37; font-size:1.1rem;"></i>
                                    @elseif($idx === 1)
                                        <i class="bi bi-trophy-fill" style="color:#a0a0a0; font-size:1rem;"></i>
                                    @elseif($idx === 2)
                                        <i class="bi bi-trophy-fill" style="color:#cd7f32; font-size:.95rem;"></i>
                                    @else
                                        <span class="text-muted">{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $stand->nombre }}</strong></td>
                                <td class="text-muted">{{ $stand->platillo ?: '—' }}</td>
                                <td class="text-muted" style="font-size:.82rem;">{{ $stand->encargado ?: '—' }}</td>
                                <td>
                                    <span class="badge-visits">{{ $stand->visits_count }}</span>
                                </td>
                                <td style="min-width:120px;">
                                    @php $pct = $maxVisits > 0 ? round(($stand->visits_count / $maxVisits) * 100) : 0; @endphp
                                    <div class="progress" style="height:10px; border-radius:5px;">
                                        <div class="progress-bar"
                                             style="width:{{ $pct }}%;
                                                    background: linear-gradient(90deg,#002395,#0046c8);">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay datos aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats por sexo --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Participantes por sexo
            </div>
            <div class="card-body">
                @php
                    $sexLabels = ['M' => 'Masculino', 'F' => 'Femenino', 'O' => 'Otro'];
                    $sexColors = ['M' => '#002395', 'F' => '#ED2939', 'O' => '#6c757d'];
                @endphp

                @foreach(['M','F','O'] as $s)
                    @php $count = $bySex[$s] ?? 0; $pct = $totalParticipants > 0 ? round(($count/$totalParticipants)*100) : 0; @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:.875rem; font-weight:500;">{{ $sexLabels[$s] }}</span>
                            <span style="font-size:.82rem; color:#6c757d;">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="progress" style="height:10px; border-radius:5px;">
                            <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $sexColors[$s] }};"></div>
                        </div>
                    </div>
                @endforeach

                <hr>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted" style="font-size:.85rem;">Promedio visitas/participante</span>
                    <strong>
                        {{ $totalParticipants > 0 ? round($totalVisits / $totalParticipants, 1) : 0 }}
                    </strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-muted" style="font-size:.85rem;">Sin ninguna visita</span>
                    <strong>{{ $totalParticipants - $activeParticipants }}</strong>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-download me-2 text-primary"></i>Acciones
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('stands.index') }}" class="btn btn-franco">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>Ver estands
                </a>
                <a href="{{ route('participants.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people-fill me-2"></i>Ver participantes
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-2"></i>Imprimir reporte
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
