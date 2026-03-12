@extends('layouts.app')

@section('title', 'Reporte de visitas')
@section('page-title', 'Reporte de visitas por estand')

@push('styles')
<style>
.timeline-item {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    font-size: 0.92rem;
    background: #f8f9fa;
    border-radius: 6px;
    padding: 6px 12px;
    margin-bottom: 4px;
}
.timeline-time {
    color: #002395;
    font-weight: bold;
    min-width: 90px;
}
.timeline-user, .timeline-stand {
    color: #333;
}
@media (max-width: 768px) {
    .timeline-item {
        font-size: 0.85rem;
        padding: 4px 6px;
    }
}
@media (max-width: 576px) {
    .timeline-item {
        font-size: 0.78rem;
    }
}

/* Modal flotante para detalle de gráficas */
.chart-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(3px);
}
.chart-modal {
    display: none;
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 16px 48px rgba(0,0,0,0.25);
    width: 90vw;
    max-width: 850px;
    max-height: 85vh;
    overflow: hidden;
    animation: modalIn .25s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: translate(-50%, -50%) scale(0.92); }
    to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
}
.chart-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
}
.chart-modal-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #002395;
}
.chart-modal-close {
    background: none;
    border: none;
    font-size: 1.4rem;
    cursor: pointer;
    color: #6c757d;
    padding: 0 4px;
    line-height: 1;
}
.chart-modal-close:hover { color: #ED2939; }
.chart-modal-body {
    padding: 20px;
    overflow-y: auto;
    max-height: calc(85vh - 60px);
}
.chart-modal-body canvas {
    max-height: 320px;
}
.detail-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    font-size: 0.88rem;
}
.detail-table th {
    background: #f1f3f5;
    padding: 8px 12px;
    text-align: left;
    font-weight: 600;
    color: #333;
    position: sticky;
    top: 0;
}
.detail-table td {
    padding: 6px 12px;
    border-bottom: 1px solid #eee;
}
.detail-table tr:hover td { background: #f8f9fa; }
.chart-card-clickable { cursor: pointer; transition: box-shadow .2s; }
.chart-card-clickable:hover { box-shadow: 0 4px 16px rgba(0,35,149,0.15); }
.click-hint {
    font-size: 0.72rem;
    color: #adb5bd;
    float: right;
    margin-top: 2px;
}
</style>
@endpush

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

{{-- Mapa de seguimiento de visitas --}}

{{-- ═══ GRÁFICAS ═══ --}}
<div class="row g-4 mt-1">
    {{-- Gráfica: Visitas por estand (barras) --}}
    <div class="col-lg-8">
        <div class="card chart-card-clickable" onclick="openDetail('stands')">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill me-2" style="color:var(--blue-dark)"></i>Visitas por estand
                <span class="click-hint"><i class="bi bi-arrows-fullscreen"></i> Click para detalle</span>
            </div>
            <div class="card-body">
                <canvas id="chartStands" height="260"></canvas>
            </div>
        </div>
    </div>

    {{-- Gráfica: Distribución por sexo (dona) --}}
    <div class="col-lg-4">
        <div class="card chart-card-clickable" onclick="openDetail('sex')">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2" style="color:#ED2939"></i>Distribución por sexo
                <span class="click-hint"><i class="bi bi-arrows-fullscreen"></i></span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartSex" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    {{-- Gráfica: Visitas por hora (línea) --}}
    <div class="col-lg-6">
        <div class="card chart-card-clickable" onclick="openDetail('hours')">
            <div class="card-header">
                <i class="bi bi-clock-fill me-2" style="color:var(--gold)"></i>Visitas por hora del día
                <span class="click-hint"><i class="bi bi-arrows-fullscreen"></i></span>
            </div>
            <div class="card-body">
                <canvas id="chartHours" height="240"></canvas>
            </div>
        </div>
    </div>

    {{-- Top visitantes --}}
    <div class="col-lg-6">
        <div class="card chart-card-clickable" onclick="openDetail('visitors')">
            <div class="card-header">
                <i class="bi bi-star-fill me-2" style="color:var(--gold)"></i>Top 5 visitantes más activos
                <span class="click-hint"><i class="bi bi-arrows-fullscreen"></i> Click para ver todos</span>
            </div>
            <div class="card-body">
                <canvas id="chartTopVisitors" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    {{-- Gráfica: Participantes por ciudad (barras horizontales) --}}
    <div class="col-lg-6">
        <div class="card chart-card-clickable" onclick="openDetail('cities')">
            <div class="card-header">
                <i class="bi bi-geo-alt-fill me-2" style="color:#27ae60"></i>Participantes por ciudad (Top 10)
                <span class="click-hint"><i class="bi bi-arrows-fullscreen"></i> Click para ver todas</span>
            </div>
            <div class="card-body">
                <canvas id="chartCities" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span><i class="bi bi-map-fill me-2" style="color:#002395"></i>Mapa de seguimiento de visitas</span>
        <div>
            <button class="btn btn-sm btn-franco" onclick="showTab('usuario')">Por usuario</button>
            <button class="btn btn-sm btn-franco" onclick="showTab('stand')">Por estand</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div id="tab-usuario">
                <h5>Visitas por usuario</h5>
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Estand</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\Visit::with(['participant','stand'])->orderBy('visit_time','desc')->limit(100)->get() as $visit)
                            <tr>
                                <td>{{ $visit->participant->nombre ?? '—' }} {{ $visit->participant->paterno ?? '' }}</td>
                                <td>{{ $visit->stand->nombre ?? '—' }}</td>
                                <td>{{ $visit->visit_time ? $visit->visit_time->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <h6>Timeline visual</h6>
                <div style="max-height:300px; overflow-y:auto;">
                    @foreach(App\Models\Visit::with(['participant','stand'])->orderBy('visit_time','desc')->limit(30)->get() as $visit)
                        <div class="timeline-item mb-3">
                            <span class="timeline-time">{{ $visit->visit_time ? $visit->visit_time->format('d/m/Y H:i') : '—' }}</span>
                            <span class="timeline-stand">{{ $visit->stand->nombre ?? '—' }}</span>
                            <span class="timeline-user">— {{ $visit->participant->nombre ?? '—' }} {{ $visit->participant->paterno ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="tab-stand" style="display:none;">
                <h5>Visitas por estand</h5>
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Estand</th>
                            <th>Usuario</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\Visit::with(['participant','stand'])->orderBy('visit_time','desc')->limit(100)->get() as $visit)
                            <tr>
                                <td>{{ $visit->stand->nombre ?? '—' }}</td>
                                <td>{{ $visit->participant->nombre ?? '—' }} {{ $visit->participant->paterno ?? '' }}</td>
                                <td>{{ $visit->visit_time ? $visit->visit_time->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <h6>Timeline visual</h6>
                <div style="max-height:300px; overflow-y:auto;">
                    @foreach(App\Models\Visit::with(['participant','stand'])->orderBy('visit_time','desc')->limit(30)->get() as $visit)
                        <div class="timeline-item mb-3">
                            <span class="timeline-time">{{ $visit->visit_time ? $visit->visit_time->format('d/m/Y H:i') : '—' }}</span>
                            <span class="timeline-user">{{ $visit->participant->nombre ?? '—' }} {{ $visit->participant->paterno ?? '' }}</span>
                            <span class="timeline-stand">— {{ $visit->stand->nombre ?? '—' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal flotante para detalle --}}
<div class="chart-overlay" id="chartOverlay" onclick="closeDetail()"></div>
<div class="chart-modal" id="chartModal">
    <div class="chart-modal-header">
        <h5 id="modalTitle"></h5>
        <button class="chart-modal-close" onclick="closeDetail()">&times;</button>
    </div>
    <div class="chart-modal-body" id="modalBody"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
// ── Tab switcher ──
function showTab(tab) {
    document.getElementById('tab-usuario').style.display = tab === 'usuario' ? 'block' : 'none';
    document.getElementById('tab-stand').style.display = tab === 'stand' ? 'block' : 'none';
}
showTab('usuario');

// ── DATOS COMPLETOS (para modals) ──
var fullStands      = {!! json_encode($stands->map(fn($s) => ['nombre' => $s->nombre, 'platillo' => $s->platillo, 'encargado' => $s->encargado, 'visitas' => $s->visits_count])) !!};
var fullHoursData   = {!! json_encode($visitsByHour) !!};
var fullCities      = {!! json_encode($allCities) !!};
var fullVisitors    = {!! json_encode($allVisitors->map(fn($p) => ['nombre' => $p->nombre . ' ' . ($p->paterno ?? '') . ' ' . ($p->materno ?? ''), 'ciudad' => $p->ciudad ?? '—', 'visitas' => $p->visits_count])) !!};
var sexData         = { M: {{ $bySex['M'] ?? 0 }}, F: {{ $bySex['F'] ?? 0 }}, O: {{ $bySex['O'] ?? 0 }} };

// ── Modal open / close ──
var modalChart = null;
function openDetail(type) {
    var overlay = document.getElementById('chartOverlay');
    var modal   = document.getElementById('chartModal');
    var title   = document.getElementById('modalTitle');
    var body    = document.getElementById('modalBody');

    if (modalChart) { modalChart.destroy(); modalChart = null; }
    body.innerHTML = '';

    var builders = {
        stands:   buildStandsDetail,
        sex:      buildSexDetail,
        hours:    buildHoursDetail,
        visitors: buildVisitorsDetail,
        cities:   buildCitiesDetail
    };
    if (builders[type]) builders[type](title, body);

    overlay.style.display = 'block';
    modal.style.display   = 'block';
}
function closeDetail() {
    document.getElementById('chartOverlay').style.display = 'none';
    document.getElementById('chartModal').style.display   = 'none';
    if (modalChart) { modalChart.destroy(); modalChart = null; }
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDetail(); });

// ── Helpers ──
function makeCanvas() {
    var c = document.createElement('canvas');
    c.style.maxHeight = '320px';
    return c;
}
function makeTable(headers, rows) {
    var html = '<div style="max-height:350px;overflow-y:auto;margin-top:12px;"><table class="detail-table"><thead><tr>';
    headers.forEach(function(h) { html += '<th>' + h + '</th>'; });
    html += '</tr></thead><tbody>';
    rows.forEach(function(r) {
        html += '<tr>';
        r.forEach(function(c) { html += '<td>' + c + '</td>'; });
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    return html;
}

// ── STANDS detail ──
function buildStandsDetail(title, body) {
    title.textContent = 'Visitas por estand — Detalle completo';
    var canvas = makeCanvas();
    body.appendChild(canvas);
    modalChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: fullStands.map(function(s) { return s.nombre; }),
            datasets: [{
                label: 'Visitas',
                data: fullStands.map(function(s) { return s.visitas; }),
                backgroundColor: 'rgba(0,35,149,0.75)',
                borderColor: '#002395', borderWidth: 1, borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { ticks: { maxRotation: 45, font: { size: 11 } } } }
        }
    });
    var rows = fullStands.map(function(s, i) { return [i + 1, s.nombre, s.platillo || '—', s.encargado || '—', s.visitas]; });
    body.insertAdjacentHTML('beforeend', '<h6 style="margin-top:20px;color:#002395;">Tabla completa (' + fullStands.length + ' estands)</h6>');
    body.insertAdjacentHTML('beforeend', makeTable(['#', 'Estand', 'Platillo', 'Encargado', 'Visitas'], rows));
}

// ── SEX detail ──
function buildSexDetail(title, body) {
    title.textContent = 'Distribución por sexo — Detalle';
    var canvas = makeCanvas();
    body.appendChild(canvas);
    var total = sexData.M + sexData.F + sexData.O;
    modalChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Masculino', 'Femenino', 'Otro'],
            datasets: [{ data: [sexData.M, sexData.F, sexData.O], backgroundColor: ['#002395', '#ED2939', '#6c757d'], borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, cutout: '55%', plugins: { legend: { position: 'bottom' } } }
    });
    var pM = total > 0 ? (sexData.M / total * 100).toFixed(1) : 0;
    var pF = total > 0 ? (sexData.F / total * 100).toFixed(1) : 0;
    var pO = total > 0 ? (sexData.O / total * 100).toFixed(1) : 0;
    body.insertAdjacentHTML('beforeend', makeTable(
        ['Sexo', 'Cantidad', 'Porcentaje'],
        [['Masculino', sexData.M, pM + '%'], ['Femenino', sexData.F, pF + '%'], ['Otro', sexData.O, pO + '%'], ['<strong>Total</strong>', '<strong>' + total + '</strong>', '<strong>100%</strong>']]
    ));
}

// ── HOURS detail ──
function buildHoursDetail(title, body) {
    title.textContent = 'Visitas por hora del día — Detalle completo';
    var canvas = makeCanvas();
    body.appendChild(canvas);
    var labels = [], data = [], totalH = 0;
    for (var h = 0; h < 24; h++) {
        labels.push(h + ':00');
        var v = fullHoursData[h] || 0;
        data.push(v);
        totalH += v;
    }
    modalChart = new Chart(canvas, {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'Visitas', data: data, borderColor: '#d4af37', backgroundColor: 'rgba(212,175,55,0.15)', fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#d4af37' }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
    var rows = [];
    for (var h = 0; h < 24; h++) {
        var v = fullHoursData[h] || 0;
        var pct = totalH > 0 ? (v / totalH * 100).toFixed(1) : 0;
        rows.push([h + ':00 - ' + h + ':59', v, pct + '%']);
    }
    body.insertAdjacentHTML('beforeend', '<h6 style="margin-top:20px;color:#002395;">Desglose por hora (Total: ' + totalH + ')</h6>');
    body.insertAdjacentHTML('beforeend', makeTable(['Hora', 'Visitas', '% del total'], rows));
}

// ── VISITORS detail ──
function buildVisitorsDetail(title, body) {
    title.textContent = 'Todos los visitantes con visitas (' + fullVisitors.length + ')';
    var canvas = makeCanvas();
    body.appendChild(canvas);
    var top20 = fullVisitors.slice(0, 20);
    modalChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: top20.map(function(v) { return v.nombre.substring(0, 25); }),
            datasets: [{ label: 'Visitas', data: top20.map(function(v) { return v.visitas; }), backgroundColor: 'rgba(212,175,55,0.75)', borderColor: '#d4af37', borderWidth: 1, borderRadius: 4 }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
    var rows = fullVisitors.map(function(v, i) { return [i + 1, v.nombre, v.ciudad, v.visitas]; });
    body.insertAdjacentHTML('beforeend', '<h6 style="margin-top:20px;color:#002395;">Lista completa</h6>');
    body.insertAdjacentHTML('beforeend', makeTable(['#', 'Nombre', 'Ciudad', 'Visitas'], rows));
}

// ── CITIES detail ──
function buildCitiesDetail(title, body) {
    title.textContent = 'Participantes por ciudad — Todas las ciudades (' + Object.keys(fullCities).length + ')';
    var canvas = makeCanvas();
    body.appendChild(canvas);
    var keys = Object.keys(fullCities);
    var vals = Object.values(fullCities);
    modalChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: keys,
            datasets: [{ label: 'Participantes', data: vals, backgroundColor: 'rgba(39,174,96,0.7)', borderColor: '#27ae60', borderWidth: 1, borderRadius: 6 }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
    var total = vals.reduce(function(a, b) { return a + b; }, 0);
    var rows = keys.map(function(k, i) {
        var pct = total > 0 ? (fullCities[k] / total * 100).toFixed(1) : 0;
        return [i + 1, k, fullCities[k], pct + '%'];
    });
    body.insertAdjacentHTML('beforeend', '<h6 style="margin-top:20px;color:#002395;">Tabla completa</h6>');
    body.insertAdjacentHTML('beforeend', makeTable(['#', 'Ciudad', 'Participantes', '% del total'], rows));
}

// ════════════════════════════════════
// ── GRÁFICAS PRINCIPALES (dashboard) ──
// ════════════════════════════════════

// ── Visitas por estand (Bar) ──
new Chart(document.getElementById('chartStands'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stands->pluck('nombre')) !!},
        datasets: [{
            label: 'Visitas',
            data: {!! json_encode($stands->pluck('visits_count')) !!},
            backgroundColor: 'rgba(0,35,149,0.75)',
            borderColor: '#002395',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } },
            x: { ticks: { maxRotation: 45, minRotation: 25, font: { size: 11 } } }
        }
    }
});

// ── Distribución por sexo (Doughnut) ──
new Chart(document.getElementById('chartSex'), {
    type: 'doughnut',
    data: {
        labels: ['Masculino', 'Femenino', 'Otro'],
        datasets: [{
            data: [{{ $bySex['M'] ?? 0 }}, {{ $bySex['F'] ?? 0 }}, {{ $bySex['O'] ?? 0 }}],
            backgroundColor: ['#002395', '#ED2939', '#6c757d'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
        }
    }
});

// ── Visitas por hora (Line) ──
(function() {
    var hoursData = {!! json_encode($visitsByHour) !!};
    var labels = [], data = [];
    for (var h = 0; h < 24; h++) {
        labels.push(h + ':00');
        data.push(hoursData[h] || 0);
    }
    new Chart(document.getElementById('chartHours'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visitas',
                data: data,
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212,175,55,0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#d4af37'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { ticks: { maxRotation: 45, font: { size: 10 } } }
            }
        }
    });
})();

// ── Participantes por ciudad (Horizontal Bar) ──
new Chart(document.getElementById('chartCities'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($byCity->keys()) !!},
        datasets: [{
            label: 'Participantes',
            data: {!! json_encode($byCity->values()) !!},
            backgroundColor: 'rgba(39,174,96,0.7)',
            borderColor: '#27ae60',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// ── Top 5 visitantes (Bar) ──
new Chart(document.getElementById('chartTopVisitors'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($topVisitors->map(fn($p) => $p->nombre . ' ' . ($p->paterno ?? ''))) !!},
        datasets: [{
            label: 'Visitas',
            data: {!! json_encode($topVisitors->pluck('visits_count')) !!},
            backgroundColor: [
                'rgba(212,175,55,0.8)',
                'rgba(192,192,192,0.8)',
                'rgba(205,127,50,0.8)',
                'rgba(0,35,149,0.6)',
                'rgba(237,41,57,0.6)'
            ],
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>
@endpush
