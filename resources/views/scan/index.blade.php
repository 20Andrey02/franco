@extends('layouts.app')

@section('title', 'Escanear QR')
@section('page-title', 'Escanear Código QR')

@section('content')
<div class="row g-4">

    {{-- Selector de Estand --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Seleccionar estand
            </div>
            <div class="card-body">
                <label for="stand-select" class="form-label">Estand activo</label>
                <select id="stand-select" class="form-select">
                    <option value="">— Elige un estand —</option>
                    @foreach($stands as $stand)
                        <option value="{{ $stand->id }}">{{ $stand->nombre }}</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-2">
                    Selecciona el estand donde estás ubicado antes de escanear.
                </small>
            </div>
        </div>

        {{-- Instrucciones --}}
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2 text-primary"></i>Instrucciones
            </div>
            <div class="card-body" style="font-size:.875rem; color:#555;">
                <ol class="ps-3 mb-0" style="line-height:2;">
                    <li>Selecciona tu estand.</li>
                    <li>Apunta la cámara al QR del gafete.</li>
                    <li>La visita se registrará automáticamente.</li>
                    <li>Puedes ingresar el código manualmente si la cámara no funciona.</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Scanner --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-qr-code-scan me-2 text-primary"></i>Registrar visita — Escanear QR
            </div>
            <div class="card-body">

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

    {{-- Últimas visitas registradas en esta sesión --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>Visitas registradas en esta sesión
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Participante</th>
                                <th>Estand</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody id="session-visits">
                            <tr id="no-visits-row">
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox d-block fs-4 mb-1"></i>
                                    Aún no se han registrado visitas en esta sesión.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
let lastScanned  = '';
let scanCooldown = false;

const resultBox   = document.getElementById('scan-result');
const standSelect = document.getElementById('stand-select');

function getStandId() {
    const val = standSelect.value;
    if (!val) {
        showResult(false, 'Primero selecciona un estand.');
        return null;
    }
    return parseInt(val);
}

function showResult(success, message) {
    resultBox.className = 'alert ' + (success ? 'alert-success' : 'alert-warning') + ' d-block';
    resultBox.innerHTML = '<i class="bi bi-' + (success ? 'check-circle-fill' : 'exclamation-triangle-fill') + ' me-2"></i>' + message;
    setTimeout(() => { resultBox.classList.remove('d-block'); resultBox.classList.add('d-none'); }, 6000);
}

function prependSessionRow(participante, standName) {
    const tbody = document.getElementById('session-visits');
    const noRow = document.getElementById('no-visits-row');
    if (noRow) noRow.remove();

    const now = new Date();
    const fmt = now.toLocaleTimeString('es-MX');
    const row = `<tr style="background:#eaffea;">
        <td><strong>${participante}</strong></td>
        <td>${standName}</td>
        <td class="text-muted" style="font-size:.82rem;">${fmt}</td>
    </tr>`;
    tbody.insertAdjacentHTML('afterbegin', row);
}

function registerVisit(code) {
    if (!code) { showResult(false, 'Ingresa un código QR.'); return; }
    const standId = getStandId();
    if (!standId) return;
    if (scanCooldown) return;
    scanCooldown = true;
    setTimeout(() => scanCooldown = false, 2000);

    const standName = standSelect.options[standSelect.selectedIndex].text;

    fetch('{{ url("/visit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: code, stand_id: standId })
    })
    .then(r => r.json())
    .then(data => {
        showResult(data.success,
            data.message + (data.success ? ' — <b>Visitas restantes: ' + data.visitas_restantes + '</b>' : ''));
        if (data.success) {
            prependSessionRow(data.participante, standName);
            const mc = document.getElementById('manual-code');
            if(mc) mc.value = '';
        }
    })
    .catch(() => showResult(false, 'Error de conexión.'));
}

// ── Cámara ──────────────────────────────────────────────
document.getElementById('tab-cam').addEventListener('shown.bs.tab', startScanner);
document.getElementById('tab-cam').addEventListener('hidden.bs.tab', stopScanner);

let html5Qr = null;

function startScanner() {
    if (html5Qr) return;
    html5Qr = new Html5Qrcode("qr-reader");
    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras.length) { showResult(false, 'No se encontró cámara.'); return; }
        html5Qr.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                if (decodedText !== lastScanned) {
                    lastScanned = decodedText;
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
        ).catch(e => showResult(false, 'Error al iniciar cámara: ' + e));
    }).catch(() => showResult(false, 'No se pudo acceder a la cámara.'));
}

function stopScanner() {
    if (html5Qr) {
        html5Qr.stop().then(() => { html5Qr.clear(); html5Qr = null; }).catch(() => {});
    }
}

window.addEventListener('load', startScanner);
window.addEventListener('beforeunload', stopScanner);

document.getElementById('manual-code').addEventListener('keydown', e => {
    if (e.key === 'Enter') registerVisit(e.target.value.trim());
});
</script>
@endpush
