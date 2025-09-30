@extends('layouts.doctor')
@section('title', 'Mis Citas (Doctor)')
@section('activeSidebar', 'citas')

@push('styles')
<style>
  .appointments-page{ width:min(1100px,100%); margin:84px auto 36px; padding:0 clamp(16px,2.4vw,28px) 36px; }

  .appointments-card{ background:#fff; border:1px solid var(--clr-border); border-radius:var(--card-border-radius); box-shadow:var(--box-shadow); overflow:hidden; }

  .appointments-header{ display:flex; justify-content:space-between; align-items:center; padding:16px 18px; border-bottom:1px solid var(--clr-border); }
  .appointments-title{ display:flex; align-items:center; gap:.6rem; font-weight:800; }

  .btn{ display:inline-flex; align-items:center; gap:.35rem; padding:.5rem .9rem; border-radius:10px; font-weight:700; border:1px solid var(--clr-border); text-decoration:none; cursor:pointer; font-size:.9rem; white-space:nowrap; }
  .btn-success{ background:var(--clr-success); color:#fff; border-color:var(--clr-success); }
  .btn-outline-success{ background:rgba(26,127,92,.08); border:1px solid rgba(26,127,92,.35); color:var(--clr-success); }
  .btn-outline-danger{ background:rgba(178,58,72,.08); border:1px solid rgba(178,58,72,.32); color:var(--clr-danger); }
  .btn-mark-done{ background:rgba(37,99,235,.08); border:1px solid rgba(37,99,235,.3); color:#2563eb; }
  .btn-mark-done:hover{ background:#2563eb; color:#fff; }

  .appointments-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .appointments-table thead th{ background:var(--clr-primary); color:#fff; font-weight:700; padding:12px 10px; }
  .appointments-table td{ padding:12px 10px; border-bottom:1px solid var(--clr-border); vertical-align:middle; }

  .cell-actions{ display:flex; gap:.5rem; align-items:center; }
  .cell-actions .btn-reagendar{ margin-left:auto; } /* empuja REAGENDAR a la derecha */

  .badge{ display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .55rem; border-radius:999px; font-size:.8rem; font-weight:700; }
  .bg-warning{ background:rgba(182,130,42,.12); color:#8b5a12; }
  .bg-info{ background:rgba(37,99,235,.12); color:#2563eb; }
  .bg-danger{ background:rgba(178,58,72,.12); color:#8b2a33; }
  .bg-success{ background:rgba(26,127,92,.12); color:#125c40; }
</style>
@endpush

@section('content')
<section class="appointments-page">
  <div class="appointments-card">
    <div class="appointments-header">
      <div class="appointments-title">
        <span class="material-symbols-outlined">calendar_month</span>
        <h1>Mis Citas (Doctor)</h1>
      </div>
      <button id="btn-refresh" class="btn btn-success">
        <span class="material-symbols-outlined">refresh</span> Actualizar
      </button>
    </div>

    <div class="table-wrap">
      <table class="appointments-table">
        <thead>
          <tr>
            <th>Paciente</th>
            <th>Especialidad</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th style="min-width:380px">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($citas as $cita)
            @php
              $estado = $cita->estado;
              $badge = match($estado){
                'pendiente' => 'bg-warning',
                'confirmada'=> 'bg-info',
                'cancelada' => 'bg-danger',
                'realizada' => 'bg-success',
                default     => 'bg-secondary'
              };
            @endphp
            <tr>
              <td>{{ $cita->paciente->name ?? '—' }}</td>
              <td>{{ $cita->especialidad->nombre ?? '—' }}</td>
              <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
              <td><span class="badge {{ $badge }}">{{ ucfirst($estado) }}</span></td>
              <td class="cell-actions">
                {{-- Aceptar / Rechazar solo cuando está pendiente --}}
                @if($estado === 'pendiente')
                  <form action="{{ route('doctor.citas.aceptar',$cita->id) }}" method="POST">@csrf
                    <button class="btn btn-outline-success">
                      <span class="material-symbols-outlined">done</span> Aceptar
                    </button>
                  </form>
                  <form action="{{ route('doctor.citas.rechazar',$cita->id) }}" method="POST" onsubmit="return confirm('¿Rechazar cita?');">@csrf
                    <button class="btn btn-outline-danger">
                      <span class="material-symbols-outlined">close</span> Rechazar
                    </button>
                  </form>
                @endif

                {{-- Mostrar "Realizada" ÚNICAMENTE cuando está confirmada --}}
                @if($estado === 'confirmada')
                  <form action="{{ route('doctor.citas.realizar',$cita->id) }}" method="POST">@csrf
                    <button class="btn btn-mark-done">
                      <span class="material-symbols-outlined">check_circle</span> Realizada
                    </button>
                  </form>
                @endif

                <a href="{{ route('paciente.editar-cita',$cita->id) }}" class="btn btn-success btn-reagendar">
                  <span class="material-symbols-outlined">event</span> Reagendar
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
