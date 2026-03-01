@extends('layouts.app')

@section('title', 'Nuevo estand')
@section('page-title', 'Nuevo estand')

@section('topbar-actions')
    <a href="{{ route('stands.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-square-fill me-2 text-primary"></i>Datos del estand
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('stands.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del estand <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre') }}" required
                                   placeholder="Ej. Estand 1 — Crêpes">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Platillo / Preparación</label>
                            <input type="text" name="platillo" class="form-control"
                                   value="{{ old('platillo') }}"
                                   placeholder="Ej. Quiche Lorraine">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                      placeholder="Breve descripción del platillo o del estand…">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Encargado / Equipo</label>
                            <input type="text" name="encargado" class="form-control"
                                   value="{{ old('encargado') }}"
                                   placeholder="Nombre del alumno o equipo responsable">
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-franco w-100">
                                <i class="bi bi-save me-2"></i>Registrar estand
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
