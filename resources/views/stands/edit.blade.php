@extends('layouts.app')

@section('title', 'Editar estand')
@section('page-title', 'Editar estand')

@section('topbar-actions')
    <a href="{{ route('stands.show', $stand) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Cancelar
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Editando: <strong>{{ $stand->nombre }}</strong>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('stands.update', $stand) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del estand <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre', $stand->nombre) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Platillo</label>
                            <input type="text" name="platillo" class="form-control"
                                   value="{{ old('platillo', $stand->platillo) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $stand->descripcion) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Encargado / Equipo</label>
                            <input type="text" name="encargado" class="form-control"
                                   value="{{ old('encargado', $stand->encargado) }}">
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-franco w-100">
                                <i class="bi bi-save me-1"></i> Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
