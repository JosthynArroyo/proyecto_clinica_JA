@extends('layouts.doctor')
@section('title', 'Mis Citas (Doctor)')
@section('activeSidebar', 'citas')
@push('styles')
    <style>
        .appointments-page {
            max-width: 900px;
            margin: 1.5rem auto 0;
            background-color: var(--clr-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            padding: var(--card-padding);
        }
        .appointments-page h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--clr-dark);
        }
        .appointments-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .appointments-page .btn {
            padding: 0.4rem 0.8rem;
            border-radius: var(--border-radius-1);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: var(--clr-primary);
            color: #fff;
        }
        .btn-primary:hover {
            opacity: 0.85;
        }
        .btn-outline-success {
            background-color: transparent;
            border: 1px solid var(--clr-success);
            color: var(--clr-success);
        }
        .btn-outline-success:hover {
            background-color: var(--clr-success);
            color: #fff;
        }
        .btn-outline-danger {
            background-color: transparent;
            border: 1px solid var(--clr-danger);
            color: var(--clr-danger);
        }
        .btn-outline-danger:hover {
            background-color: var(--clr-danger);
            color: #fff;
        }
        .btn-success {
            background-color: var(--clr-success);
            color: #000;
        }
        .btn-success:hover {
            opacity: 0.85;
        }
        .appointments-page .alert-success,
        .appointments-page .alert-danger {
            padding: 0.8rem 1rem;
            border-radius: var(--border-radius-1);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .appointments-page .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .appointments-page .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .appointments-table-wrapper {
            overflow-x: auto;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .appointments-table thead {
            background-color: var(--clr-primary);
            color: var(--clr-white);
        }
        .appointments-table th,
        .appointments-table td {
            padding: 0.8rem 0.6rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        .badge {
            padding: 0.3rem 0.6rem;
            border-radius: var(--border-radius-1);
            font-weight: 600;
            font-size: 0.85rem;
        }
        .bg-warning { background-color: var(--clr-warning); color: #000; }
        .bg-info { background-color: var(--clr-info-light); color: #000; }
        .bg-danger { background-color: var(--clr-danger); color: #fff; }
        .bg-success { background-color: var(--clr-success); color: #000; }
        .bg-secondary { background-color: #ccc; color: #000; }
        .appointments-table .d-flex {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        @media (max-width: 768px) {
            .appointments-page {
                padding: 1.2rem;
            }
            .appointments-table th,
            .appointments-table td {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush
@section('content')
    <section class="appointments-page">
        <div class="appointments-actions">
            <h1>Mis Citas (Doctor)</h1>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">← Volver al Dashboard</a>
        </div>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif
        @if($citas->isEmpty())
            <p>No tienes citas asignadas.</p>
        @else
            <div class="appointments-table-wrapper">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Especialidad</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                            <tr>
                                <td>{{ $cita->paciente->name ?? '—' }}</td>
                                <td>{{ $cita->especialidad->nombre ?? '—' }}</td>
                                <td>{{ $cita->fecha }}</td>
                                <td>{{ $cita->hora }}</td>
                                <td>
                                    @switch($cita->estado)
                                        @case('pendiente')   <span class="badge bg-warning">Pendiente</span> @break
                                        @case('confirmada')  <span class="badge bg-info">Confirmada</span> @break
                                        @case('cancelada')   <span class="badge bg-danger">Cancelada</span> @break
                                        @case('realizada')   <span class="badge bg-success">Realizada</span> @break
                                        @default             <span class="badge bg-secondary">{{ $cita->estado }}</span>
                                    @endswitch
                                </td>
                                <td class="d-flex">
                                    @if($cita->estado == 'pendiente')
                                        <form action="{{ route('doctor.citas.aceptar', $cita->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-success">Aceptar</button>
                                        </form>
                                        <form action="{{ route('doctor.citas.rechazar', $cita->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-danger">Rechazar</button>
                                        </form>
                                    @endif
                                    <a class="btn btn-success" href="{{ route('doctor.citas.editar', $cita->id) }}">Reagendar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection