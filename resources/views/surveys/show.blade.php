@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">📋 Encuesta de Satisfacción</h2>
                    <p class="mb-0 mt-2">Francofonía — Evento Cultural</p>
                </div>

                <div class="card-body p-5">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i>
                        <strong>Bienvenido {{ $participant->nombre }}!</strong>
                        <p class="mb-0 mt-2">Tu opinión es muy importante para nosotros. Completa esta breve encuesta en aproximadamente 2 minutos.</p>
                    </div>

                    <form action="{{ route('survey.store') }}" method="POST" id="surveyForm">
                        @csrf
                        <input type="hidden" name="participant_id" value="{{ $participant->id }}">

                        <!-- Pregunta 1 -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-3">
                                    <span class="badge bg-primary me-2">1</span>
                                    ¿Qué tal fue tu experiencia general en el evento?
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input rating-input" type="radio" name="q1" id="q1_{{ $i }}" value="{{ $i }}" required>
                                            <label class="form-check-label rating-label" for="q1_{{ $i }}">
                                                @if ($i == 1)
                                                    😞 Muy Mala
                                                @elseif ($i == 2)
                                                    😕 Mala
                                                @elseif ($i == 3)
                                                    😐 Regular
                                                @elseif ($i == 4)
                                                    😊 Buena
                                                @elseif ($i == 5)
                                                    😍 Excelente
                                                @endif
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('q1')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Pregunta 2 -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-3">
                                    <span class="badge bg-primary me-2">2</span>
                                    ¿Disfrutaste de la comida y bebidas ofrecidas?
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input rating-input" type="radio" name="q2" id="q2_{{ $i }}" value="{{ $i }}" required>
                                            <label class="form-check-label rating-label" for="q2_{{ $i }}">
                                                @if ($i == 1)
                                                    ⭐
                                                @elseif ($i == 5)
                                                    ⭐⭐⭐⭐⭐
                                                @else
                                                    {{ str_repeat('⭐', $i) }}
                                                @endif
                                                {{ $i }}
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('q2')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Pregunta 3 -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-3">
                                    <span class="badge bg-primary me-2">3</span>
                                    ¿Los stands estaban bien organizados y señalizados?
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input rating-input" type="radio" name="q3" id="q3_{{ $i }}" value="{{ $i }}" required>
                                            <label class="form-check-label rating-label" for="q3_{{ $i }}">
                                                {{ $i }}
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('q3')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Pregunta 4 -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-3">
                                    <span class="badge bg-primary me-2">4</span>
                                    ¿Recomendarías este evento a otros?
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input rating-input" type="radio" name="q4" id="q4_{{ $i }}" value="{{ $i }}" required>
                                            <label class="form-check-label rating-label" for="q4_{{ $i }}">
                                                @if ($i == 5)
                                                    👍 Definitivamente
                                                @elseif ($i == 1)
                                                    👎 De ninguna forma
                                                @else
                                                    Opción {{ $i }}
                                                @endif
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('q4')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Pregunta 5 -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-3">
                                    <span class="badge bg-primary me-2">5</span>
                                    ¿Volverías a un evento similar?
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input rating-input" type="radio" name="q5" id="q5_{{ $i }}" value="{{ $i }}" required>
                                            <label class="form-check-label rating-label" for="q5_{{ $i }}">
                                                {{ $i }}
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('q5')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Comentarios -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="comentarios" class="form-label fw-bold">💬 Comentarios adicionales (opcional)</label>
                                <textarea class="form-control" id="comentarios" name="comentarios" rows="4" placeholder="Comparte tus sugerencias o comentarios..."></textarea>
                                <small class="text-muted">Máximo 500 caracteres</small>
                                @error('comentarios')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Enviar Encuesta
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i> Tus datos están seguros y se usan solo para mejorar futuros eventos.
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-label {
        cursor: pointer;
        padding: 0.5rem 1rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        transition: all 0.3s ease;
        user-select: none;
    }

    .rating-input:checked ~ .rating-label,
    .rating-label:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .form-check {
        margin: 0;
    }

    .form-check-input[type="radio"] {
        display: none;
    }

    .card {
        border-radius: 12px;
        border: none;
    }

    .card-header {
        border-radius: 12px 12px 0 0;
    }
</style>
@endsection
