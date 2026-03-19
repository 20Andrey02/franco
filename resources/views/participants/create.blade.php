@extends('layouts.app')

@section('title', 'Registrar participante')
@section('page-title', 'Nuevo participante')

@section('topbar-actions')
    <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus-fill me-2 text-primary"></i>Datos del participante
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('participants.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre') }}" required placeholder="Nombre(s)">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Apellido paterno <span class="text-danger">*</span></label>
                            <input type="text" name="paterno" class="form-control"
                                   value="{{ old('paterno') }}" required placeholder="Paterno">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Apellido materno</label>
                            <input type="text" name="materno" class="form-control"
                                   value="{{ old('materno') }}" placeholder="Materno">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control"
                                   value="{{ old('ciudad') }}" placeholder="Ej. Guadalajara">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Municipio</label>
                            <input type="text" name="municipio" class="form-control"
                                   value="{{ old('municipio') }}" placeholder="Ej. Zapopan">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Correo electrónico <span class="text-muted">(opcional)</span></label>
                            <input type="email" name="correo" class="form-control"
                                   value="{{ old('correo') }}" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" class="form-select">
                                <option value="M" {{ old('sexo')=='M'?'selected':'' }}>Masculino</option>
                                <option value="F" {{ old('sexo')=='F'?'selected':'' }}>Femenino</option>
                                <option value="O" {{ old('sexo','O')=='O'?'selected':'' }}>Otro / Prefiero no decir</option>
                            </select>
                        </div>

                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-franco w-100">
                                <i class="bi bi-qr-code me-2"></i>Registrar y generar QR
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection