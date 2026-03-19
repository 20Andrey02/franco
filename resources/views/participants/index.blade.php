@extends('layouts.app')

@section('title', 'Participantes')
@section('page-title', 'Participantes')

@section('topbar-actions')
    <a href="{{ route('participants.create') }}" class="btn btn-franco">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo participante
    </a>
    <form action="{{ route('participants.send.badge.all') }}" method="POST" class="d-inline ms-2"
          onsubmit="return confirm('¿Enviar gafete por correo a TODOS los participantes con correo?')">
        @csrf
        <button class="btn btn-outline-primary">
            <i class="bi bi-envelope-fill me-1"></i> Enviar a todos
        </button>
    </form>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<form id="sendSelectedForm" action="{{ route('participants.send.badge.selected') }}" method="POST" style="display:none;">
@csrf
<div id="selectedIdsContainer"></div>
</form>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people-fill me-2 text-primary"></i>Lista de participantes</span>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSendSelected" style="display:none;">
                <i class="bi bi-envelope me-1"></i> Enviar seleccionados (<span id="selectedCount">0</span>)
            </button>
            <span class="badge bg-secondary">{{ $participants->total() }} registrados</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th style="width:40px"><input type="checkbox" id="checkAll" title="Seleccionar todos"></th>
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
                        <td><input type="checkbox" value="{{ $p->id }}" class="row-check"></td>
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
                                <a href="{{ route('participants.badge.pdf', $p) }}"
                                   class="btn btn-sm btn-outline-info" title="Descargar PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                @if($p->correo)
                                <form action="{{ route('participants.send.badge', $p) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Enviar gafete a {{ $p->correo }}?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Enviar por correo">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </form>
                                @endif
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
                        <td colspan="9" class="text-center text-muted py-5">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const btnSend = document.getElementById('btnSendSelected');
    const countSpan = document.getElementById('selectedCount');

    function updateCount() {
        const checked = document.querySelectorAll('.row-check:checked').length;
        countSpan.textContent = checked;
        btnSend.style.display = checked > 0 ? 'inline-block' : 'none';
    }

    checkAll.addEventListener('change', function() {
        rowChecks.forEach(cb => cb.checked = this.checked);
        updateCount();
    });

    rowChecks.forEach(cb => cb.addEventListener('change', updateCount));

    btnSend.addEventListener('click', function() {
        if (!confirm('¿Enviar gafete a los participantes seleccionados?')) return;
        const container = document.getElementById('selectedIdsContainer');
        container.innerHTML = '';
        document.querySelectorAll('.row-check:checked').forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
        document.getElementById('sendSelectedForm').submit();
    });
});
</script>
@endsection