@extends('layouts.app')

@section('title', $stand->nombre . ' — Escanear QR')
@section('page-title', $stand->nombre)

@section('topbar-actions')
    <a href="{{ route('stands.edit', $stand) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-pencil me-1"></i> Editar
    </a>
    <a href="{{ route('stands.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
<div class="row g-4">

    {{-- Info del stand --}}
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-info-circle-fill me-2 text-primary"></i>Información del estand
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr>
                        <th class="text-muted" style="width:40%">Platillo</th>
                        <td><strong>{{ $stand->platillo ?: '—' }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Encargado</th>
                        <td>{{ $stand->encargado ?: '—' }}</td>
                    </tr>
                    @if($stand->descripcion)
                    <tr>
                        <th class="text-muted">Descripción</th>
                        <td>{{ $stand->descripcion }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-muted">Total visitas</th>
                        <td><span class="badge-visits">{{ $totalVisits }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- QR Scanner --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-qr-code-scan me-2 text-primary"></i>Registrar visita — Escanear QR
            </div>
            <div class="card-body">

                {{-- Resultado del escaneo --}}
                <div id="scan-result" class="alert d-none mb-3" role="alert"></div>

                {{-- Tabs: Cámara / Manual --}}
                <ul class="nav nav-tabs mb-3" id="scanTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="tab-cam" data-bs-toggle="tab"
                                data-bs-target="#panel-cam" type="button">
                            <i class="bi bi-camera-video me-1"></i> Cámara
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="tab-manual" data-bs-toggle="tab"
                                data-bs-target="#panel-manual" type="button">
                            <i class="bi bi-keyboard me-1"></i> Código manual
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Cámara --}}
                    <div class="tab-pane fade show active" id="panel-cam">
                        <div id="qr-reader" style="width:100%; max-width:480px; margin:0 auto;"></div>
                        <p class="text-center text-muted mt-2" style="font-size:.8rem;">
                            Apunta la cámara al código QR del gafete del participante
                        </p>
                    </div>

                    {{-- Manual --}}
                    <div class="tab-pane fade" id="panel-manual">
                        <div class="d-flex gap-2">
                            <input type="text" id="manual-code" class="form-control"
                                   placeholder="Ej. FRANCO-000001" style="text-transform:uppercase;">
                            <button onclick="registerVisit(document.getElementById('manual-code').value.trim())"
                                    class="btn btn-franco text-nowrap">
                                <i class="bi bi-check-circle me-1"></i> Registrar
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Ingresa el código que aparece bajo el QR del gafete.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimas visitas --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Últimas visitas a este estand</span>
                <span class="badge bg-secondary">{{ $totalVisits }} total</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" id="visits-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Participante</th>
                                <th>Correo</th>
                                <th>Fecha y hora</th>
                            </tr>
                        </thead>
                        <tbody id="visits-tbody">
                            @forelse($recentVisits as $visit)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td><strong>{{ $visit->participant->nombre }} {{ $visit->participant->paterno }}</strong></td>
                                <td class="text-muted">{{ $visit->participant->correo }}</td>
                                <td class="text-muted" style="font-size:.82rem;">
                                    {{ \Carbon\Carbon::parse($visit->visit_time)->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4" id="no-visits-msg">
                                    <i class="bi bi-inbox d-block fs-4 mb-1"></i>
                                    Aún no hay visitas en este estand.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- html5-qrcode --}}
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
const STAND_ID   = {{ $stand->id }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
let lastScanned  = '';
let scanCooldown = false;

const resultBox = document.getElementById('scan-result');

function showResult(success, message) {
    resultBox.className = 'alert ' + (success ? 'alert-success' : 'alert-warning') + ' d-block';
    resultBox.innerHTML = '<i class="bi bi-' + (success ? 'check-circle-fill' : 'exclamation-triangle-fill') + ' me-2"></i>' + message;
    setTimeout(() => resultBox.classList.remove('d-block') || resultBox.classList.add('d-none'), 6000);
}

function prependVisitRow(participante) {
    const tbody = document.getElementById('visits-tbody');
    const noMsg = document.getElementById('no-visits-msg');
    if (noMsg) noMsg.closest('tr').remove();

    const now = new Date();
    const fmt = now.toLocaleDateString('es-MX') + ' ' + now.toLocaleTimeString('es-MX');
    const row = `<tr style="background:#eaffea;">
        <td class="text-muted">—</td>
        <td><strong>${participante}</strong></td>
        <td class="text-muted">—</td>
        <td class="text-muted" style="font-size:.82rem;">${fmt}</td>
    </tr>`;
    tbody.insertAdjacentHTML('afterbegin', row);
}

function registerVisit(code) {
    if (!code) { showResult(false, 'Ingresa un código QR.'); return; }
    if (scanCooldown) return;
    scanCooldown = true;
    setTimeout(() => scanCooldown = false, 2000);

    fetch('{{ url("/visit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: code, stand_id: STAND_ID })
    })
    .then(r => r.json())
    .then(data => {
        showResult(data.success,
            data.message + (data.success ? ' — <b>Visitas totales: ' + data.visitas_totales + '</b>' : ''));
        if (data.success) {
            prependVisitRow(data.participante);
            document.getElementById('manual-code').value = '';
        }
    })
    .catch(() => showResult(false, 'Error de conexión.'));
}

// Iniciar escáner de cámara cuando se activa la pestaña
document.getElementById('tab-cam').addEventListener('shown.bs.tab', startScanner);
document.getElementById('tab-cam').addEventListener('hidden.bs.tab', stopScanner);

let html5Qr = null;

function startScanner() {
    if (html5Qr) return;

    // La cámara requiere HTTPS o localhost en navegadores móviles
    if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        showResult(false, '⚠️ La cámara requiere HTTPS. En este dispositivo usa la pestaña "Código manual".<br><small>Para habilitar la cámara, accede al sitio por HTTPS o desde localhost.</small>');
        return;
    }

    html5Qr = new Html5Qrcode("qr-reader");
    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras.length) { showResult(false, 'No se encontró cámara. Usa la pestaña "Código manual".'); return; }
        html5Qr.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                if (decodedText !== lastScanned) {
                    lastScanned = decodedText;
                    // El QR puede contener una URL completa o solo el código
                    let code = decodedText;
                    try {
                        const url = new URL(decodedText);
                        code = url.searchParams.get('code') || decodedText;
                    } catch(e) {}
                    registerVisit(code);
                    setTimeout(() => lastScanned = '', 3000);
                }
            },
            null
        ).catch(e => {
            let msg = 'Error al iniciar cámara. ';
            if (e.toString().includes('NotAllowedError') || e.toString().includes('Permission')) {
                msg = 'Permiso de cámara denegado. Permite el acceso en la configuración de tu navegador.';
            } else if (e.toString().includes('NotFoundError')) {
                msg = 'No se encontró cámara en este dispositivo.';
            } else if (e.toString().includes('NotReadableError') || e.toString().includes('Could not start')) {
                msg = 'La cámara está en uso por otra aplicación.';
            }
            showResult(false, msg + ' Usa la pestaña "Código manual".');
        });
    }).catch(e => {
        let msg = 'No se pudo acceder a la cámara.';
        if (e.toString().includes('NotAllowedError') || e.toString().includes('Permission')) {
            msg = 'Permiso de cámara denegado. Permite el acceso en la configuración de tu navegador.';
        }
        showResult(false, msg + ' Usa la pestaña "Código manual".');
    });
}

function stopScanner() {
    if (html5Qr) {
        html5Qr.stop().then(() => { html5Qr.clear(); html5Qr = null; }).catch(() => {});
    }
}

// Auto-start scanner on load (camera tab is active by default)
window.addEventListener('load', startScanner);
window.addEventListener('beforeunload', stopScanner);

// Enter key on manual input
document.getElementById('manual-code').addEventListener('keydown', e => {
    if (e.key === 'Enter') registerVisit(e.target.value.trim());
});
</script>
@endpush
