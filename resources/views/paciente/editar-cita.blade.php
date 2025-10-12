@extends('layouts.paciente')
@section('title', 'Reagendar Cita')
@section('body-class', 'paciente-body--editar-cita')

@push('head')
    @vite('resources/css/paciente/editar-cita.css')
@endpush

@section('main')
    <div class="editar-cita-page">
        <div class="card">
            <div class="card-header">
                <div class="header-panel">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="3"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                    </svg>
                    <div>
                        <h1 class="title">Reagendar Cita</h1>
                        <p class="subtitle">Selecciona una nueva fecha y hora para tu atención.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->has('error'))
                    <div class="alert">{{ $errors->first('error') }}</div>
                @endif

                <div class="meta">
                    <div class="kpi">
                        <div class="label">Doctor</div>
                        <div class="value">{{ $cita->doctor->name ?? 'Sin asignar' }}</div>
                    </div>
                    <div class="kpi">
                        <div class="label">Especialidad</div>
                        <div class="value">{{ $cita->especialidad->nombre ?? '—' }}</div>
                    </div>
                    <div class="kpi">
                        <div class="label">Estado</div>
                        <div class="value">
                            <span class="pill {{ $cita->estado === 'pendiente' ? 'pending' : ($cita->estado === 'confirmada' ? 'info' : ($cita->estado === 'realizada' ? 'success' : 'danger')) }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('paciente.editar-cita.update', $cita->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div>
                            <label for="fecha">Nueva Fecha</label>
                            <div class="input">
                                <span class="icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="3"></rect>
                                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                                    </svg>
                                </span>
                                <input
                                    id="fecha"
                                    type="date"
                                    name="fecha"
                                    value="{{ old('fecha', $cita->fecha ? $cita->fecha->format('Y-m-d') : '') }}"
                                    min="{{ \Carbon\Carbon::now('America/Guayaquil')->toDateString() }}"
                                    required
                                >
                            </div>
                            @error('fecha')<span class="error">{{ $message }}</span>@enderror
                            <div class="help">Solo fechas futuras o la actual.</div>
                        </div>

                        <div>
                            <label for="hora">Nueva Hora</label>
                            <div class="input">
                                <span class="icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 3"></path>
                                    </svg>
                                </span>
                                <input
                                    id="hora"
                                    type="time"
                                    name="hora"
                                    value="{{ old('hora', \Carbon\Carbon::parse($cita->hora)->format('H:i')) }}"
                                    step="1800"
                                    required
                                >
                            </div>
                            @error('hora')<span class="error">{{ $message }}</span>@enderror
                            <div class="help">Formato 24 horas. Intervalos de 30 minutos (08:00, 08:30, 09:00...).</div>
                        </div>
                    </div>

                    <div class="actions">
                        <a class="btn btn-ghost" href="{{ route('paciente.citas') }}">Regresar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
