<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Francofonía') — Sistema de Estands</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --blue-dark:  #002395;
            --blue-mid:   #0035b5;
            --gold:       #d4af37;
            --gold-light: #f0d060;
            --red-fr:     #ED2939;
            --sidebar-w:  240px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f7;
            margin: 0;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--blue-dark) 0%, #001270 100%);
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,.25);
        }

        .sidebar-brand {
            padding: 28px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar-brand .flag-bar {
            display: flex;
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .flag-bar .f-blue { flex:1; background:#002395; }
        .flag-bar .f-white { flex:1; background:#fff; }
        .flag-bar .f-red  { flex:1; background:#ED2939; }

        .sidebar-brand h1 {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: .5px;
        }
        .sidebar-brand small {
            color: rgba(255,255,255,.55);
            font-size: .72rem;
            display: block;
            margin-top: 2px;
        }

        .sidebar-nav { flex: 1; padding: 16px 0; }

        .nav-section-title {
            color: rgba(255,255,255,.38);
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 14px 20px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,.72);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar-link i { font-size: 1.05rem; }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255,255,255,.09);
            border-left-color: var(--gold);
        }
        .sidebar-link.active { color: var(--gold-light); }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e3e7ef;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a2340;
            margin: 0;
        }

        .page-wrapper { padding: 28px; flex: 1; }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eef0f5;
            border-radius: 14px 14px 0 0 !important;
            padding: 16px 22px;
            font-weight: 600;
            color: #1a2340;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            border-radius: 14px;
            padding: 22px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card .stat-icon {
            font-size: 2.4rem;
            opacity: .25;
            position: absolute;
            right: 18px;
            top: 16px;
        }
        .stat-card .stat-val { font-size: 2rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: .78rem; opacity: .8; margin-top: 4px; }
        .stat-blue  { background: linear-gradient(135deg, #002395, #0046c8); }
        .stat-gold  { background: linear-gradient(135deg, #b8860b, #d4af37); }
        .stat-red   { background: linear-gradient(135deg, #c0392b, #ED2939); }
        .stat-green { background: linear-gradient(135deg, #1a7a4a, #27ae60); }

        /* ── BUTTONS ── */
        .btn-franco {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 9px 20px;
            transition: all .2s;
        }
        .btn-franco:hover {
            color: #fff;
            opacity: .88;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,35,149,.3);
        }

        .btn-gold {
            background: linear-gradient(135deg, #c9a227, var(--gold));
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 9px 20px;
            transition: all .2s;
        }
        .btn-gold:hover { color:#fff; opacity:.88; transform:translateY(-1px); }

        /* ── TABLE ── */
        .table-modern thead th {
            background: #f5f7fc;
            color: #5a6488;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            border: none;
            padding: 12px 16px;
        }
        .table-modern td {
            padding: 12px 16px;
            vertical-align: middle;
            border-color: #f0f2f7;
            font-size: .875rem;
        }
        .table-modern tbody tr:hover { background: #f8f9fd; }

        /* ── BADGE ── */
        .badge-visits {
            background: linear-gradient(135deg, #002395, #0046c8);
            color: #fff;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            padding: 3px 10px;
        }

        /* ── QR CARD ── */
        .qr-wrapper {
            background: linear-gradient(135deg, #f8f9fd, #eef0f9);
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            border: 2px dashed #c9d0e8;
        }

        /* ── ALERTS ── */
        .alert { border-radius: 10px; font-size: .875rem; }

        /* ── FORM ── */
        .form-label { font-weight: 500; color: #374151; font-size: .875rem; }
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #d1d7e8;
            font-size: .9rem;
            transition: all .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--blue-mid);
            box-shadow: 0 0 0 3px rgba(0,35,149,.12);
        }

        /* ── SCANNER ── */
        #qr-reader { border-radius: 12px; overflow: hidden; }
        #scan-result {
            border-radius: 10px;
            font-weight: 500;
        }

        /* ── PRINT ── */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-wrapper { padding: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="flag-bar">
            <div class="f-blue"></div>
            <div class="f-white"></div>
            <div class="f-red"></div>
        </div>
        <h1>Francofonía</h1>
        <small>Sistema de Estands</small>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-title">Gestión</div>

        <a href="{{ route('participants.index') }}"
           class="sidebar-link {{ request()->routeIs('participants.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Participantes
        </a>
        <a href="{{ route('participants.create') }}"
           class="sidebar-link {{ request()->routeIs('participants.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus-fill"></i> Nuevo participante
        </a>

        <div class="nav-section-title">Estands</div>

        <a href="{{ route('stands.index') }}"
           class="sidebar-link {{ request()->routeIs('stands.index') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i> Ver estands
        </a>
        <a href="{{ route('stands.create') }}"
           class="sidebar-link {{ request()->routeIs('stands.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square-fill"></i> Nuevo estand
        </a>

        <div class="nav-section-title">Reportes</div>

        <a href="{{ route('reports.index') }}"
           class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Reporte de visitas
        </a>
    </div>

    <div style="padding:16px 20px; border-top:1px solid rgba(255,255,255,.1);">
        <small style="color:rgba(255,255,255,.3); font-size:.68rem;">
            &copy; {{ date('Y') }} Francofonía
        </small>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="topbar">
        <h2 class="topbar-title">@yield('page-title', 'Panel')</h2>
        <div class="d-flex align-items-center gap-3">
            @yield('topbar-actions')
        </div>
    </div>

    <div class="page-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
