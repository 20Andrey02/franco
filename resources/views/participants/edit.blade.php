@extends('layouts.app')

@section('title', 'Editar participante')
@section('page-title', 'Editar participante')

@section('topbar-actions')
    <a href="{{ route('participants.show', $participant) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Cancelar
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Editando: <strong>{{ $participant->nombre }} {{ $participant->paterno }}</strong>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('participants.update', $participant) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre', $participant->nombre) }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Paterno <span class="text-danger">*</span></label>
                            <input type="text" name="paterno" class="form-control"
                                   value="{{ old('paterno', $participant->paterno) }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Materno</label>
                            <input type="text" name="materno" class="form-control"
                                   value="{{ old('materno', $participant->materno) }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control"
                                   value="{{ old('ciudad', $participant->ciudad) }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Municipio</label>
                            <input type="text" name="municipio" class="form-control"
                                   value="{{ old('municipio', $participant->municipio) }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Correo <span class="text-muted">(opcional)</span></label>
                            <input type="email" name="correo" class="form-control"
                                   value="{{ old('correo', $participant->correo) }}" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" class="form-select">
                                <option value="M" {{ old('sexo',$participant->sexo)==='M'?'selected':'' }}>Masculino</option>
                                <option value="F" {{ old('sexo',$participant->sexo)==='F'?'selected':'' }}>Femenino</option>
                                <option value="O" {{ old('sexo',$participant->sexo)==='O'?'selected':'' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-12 pt-2 d-flex gap-2">
                            <button type="submit" class="btn btn-franco flex-fill">
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
