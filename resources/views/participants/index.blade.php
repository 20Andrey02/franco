@extends('layouts.app')

@section('title', 'Participantes')
@section('page-title', 'Participantes')

@section('topbar-actions')
    <a href="{{ route('participants.create') }}" class="btn btn-franco">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo participante
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people-fill me-2 text-primary"></i>Lista de participantes</span>
        <span class="badge bg-secondary">{{ $participants->total() }} registrados</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre completo</th>
                        <th>Correo</th>
                        <th>Ciudad / Municipio</th>
                        <th>Sexo</th>
                        <th>Visitas</th>
                        <th>QR</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                    <tr>
                        <td class="text-muted">{{ $p->id }}</td>
                        <td>
                            <div class="fw-600">{{ $p->nombre }} {{ $p->paterno }} {{ $p->materno }}</div>
                        </td>
                        <td class="text-muted">{{ $p->correo }}</td>
                        <td class="text-muted" style="font-size:.8rem;">
                            {{ $p->ciudad }}{{ $p->ciudad && $p->municipio ? ' / ' : '' }}{{ $p->municipio }}
                        </td>
                        <td>
                            @if($p->sexo === 'M') <span class="badge bg-primary">Masculino</span>
                            @elseif($p->sexo === 'F') <span class="badge bg-danger">Femenino</span>
                            @else <span class="badge bg-secondary">Otro</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-visits">{{ $p->visits_count }}</span>
                        </td>
                        <td>
                            @if($p->qr_code)
                                <code style="font-size:.72rem; color:#0035b5;">{{ $p->qr_code }}</code>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('participants.show', $p) }}"
                                   class="btn btn-sm btn-outline-primary" title="Ver gafete">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                <a href="{{ route('participants.edit', $p) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('participants.destroy', $p) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar participante?')">
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
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No hay participantes registrados.
                            <a href="{{ route('participants.create') }}">Registrar el primero</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($participants->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
        {{ $participants->links() }}
    </div>
    @endif
</div>
@endsection