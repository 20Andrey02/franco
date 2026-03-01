@extends('layouts.app')

@section('title', 'Participante — ' . $participant->nombre . ' ' . $participant->paterno)
@section('page-title', 'Detalle del participante')

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-gold btn-sm no-print">
        <i class="bi bi-printer-fill me-1"></i> Imprimir gafete
    </button>
    <a href="{{ route('participants.edit', $participant) }}" class="btn btn-outline-secondary btn-sm no-print">
        <i class="bi bi-pencil me-1"></i> Editar
    </a>
    <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary btn-sm no-print">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')

{{-- ====== GAFETE (visible en pantalla y al imprimir) ====== --}}
<div class="row g-4">
    {{-- QR Card --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-qr-code me-2 text-primary"></i>Código QR — Gafete
            </div>
            <div class="card-body">
                <div class="qr-wrapper">
                    @if($participant->qr_code)
                        <div id="qr-svg">
                            {!! QrCode::size(200)->errorCorrection('H')->generate($qrUrl) !!}
                        </div>
                        <p class="mt-3 mb-0">
                            <code style="font-size:.8rem; color:#002395;">{{ $participant->qr_code }}</code>
                        </p>
                        <small class="text-muted d-block mt-1" style="font-size:.7rem;">
                            Escanear en cada estand
                        </small>
                    @else
                        <p class="text-muted">Sin código QR</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Info personal --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Datos personales
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr>
                        <th class="text-muted fw-500 w-35" style="width:35%">Nombre</th>
                        <td class="fw-600">{{ $participant->nombre }} {{ $participant->paterno }} {{ $participant->materno }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-500">Correo</th>
                        <td>{{ $participant->correo }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-500">Ciudad</th>
                        <td>{{ $participant->ciudad ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-500">Municipio</th>
                        <td>{{ $participant->municipio ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-500">Sexo</th>
                        <td>
                            @if($participant->sexo==='M') Masculino
                            @elseif($participant->sexo==='F') Femenino
                            @else Otro @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-500">Visitas usadas</th>
                        <td>
                            @php $used = $participant->visits->count(); @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width:120px; height:8px; border-radius:4px;">
                                    <div class="progress-bar bg-primary" style="width:{{ ($used/5)*100 }}%"></div>
                                </div>
                                <span class="badge-visits">{{ $used }}/5</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Historial de visitas --}}
    @if($participant->visits->count() > 0)
    <div class="col-12 no-print">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>Historial de visitas
            </div>
            <div class="card-body p-0">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estand visitado</th>
                            <th>Platillo</th>
                            <th>Fecha y hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participant->visits->sortByDesc('visit_time') as $v)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td><strong>{{ $v->stand->nombre ?? '—' }}</strong></td>
                            <td class="text-muted">{{ $v->stand->platillo ?? '—' }}</td>
                            <td class="text-muted" style="font-size:.82rem;">
                                {{ \Carbon\Carbon::parse($v->visit_time)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection