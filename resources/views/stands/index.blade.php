@extends('layouts.app')

@section('title', 'Estands')
@section('page-title', 'Estands')

@section('topbar-actions')
    <a href="{{ route('stands.create') }}" class="btn btn-franco">
        <i class="bi bi-plus-square-fill me-1"></i> Nuevo estand
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card stat-blue">
            <i class="bi bi-grid-3x3-gap-fill stat-icon"></i>
            <div class="stat-val">{{ $stands->count() }}</div>
            <div class="stat-label">Estands registrados</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card stat-gold">
            <i class="bi bi-eye-fill stat-icon"></i>
            <div class="stat-val">{{ $stands->sum('visits_count') }}</div>
            <div class="stat-label">Visitas totales</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card stat-green">
            <i class="bi bi-trophy-fill stat-icon"></i>
            <div class="stat-val">{{ $stands->max('visits_count') ?? 0 }}</div>
            <div class="stat-label">Máx. visitas en un estand</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Lista de estands</span>
        <span class="badge bg-secondary">{{ $stands->count() }} estands</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del estand</th>
                        <th>Platillo</th>
                        <th>Encargado / Equipo</th>
                        <th>Visitas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stands as $stand)
                    <tr>
                        <td class="text-muted">{{ $stand->id }}</td>
                        <td><strong>{{ $stand->nombre }}</strong></td>
                        <td class="text-muted">{{ $stand->platillo ?: '—' }}</td>
                        <td class="text-muted">{{ $stand->encargado ?: '—' }}</td>
                        <td>
                            <span class="badge-visits">{{ $stand->visits_count }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('stands.show', $stand) }}"
                                   class="btn btn-sm btn-outline-primary" title="Escanear QR / Visitas">
                                    <i class="bi bi-qr-code-scan"></i>
                                </a>
                                <a href="{{ route('stands.edit', $stand) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('stands.destroy', $stand) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar estand?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No hay estands registrados.
                            <a href="{{ route('stands.create') }}">Registrar el primero</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
