@extends('layouts.app')

@section('title', 'Panel médico')

@section('content')
  <div class="flex flex-col gap-8">
    <header class="flex flex-col gap-2">
      <h1 class="text-3xl font-bold text-slate-900">Agenda del día</h1>
      <p class="text-slate-600">Revisa tus indicadores y próximas consultas.</p>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($metrics as $label => $value)
        @php
          $labels = [
            'hoy' => ['Consultas hoy', 'event'],
            'realizadas' => ['Realizadas hoy', 'task_alt'],
            'pendientes' => ['Pendientes', 'pending'],
            'confirmadas_2h' => ['Confirmadas 2h', 'schedule'],
            'realizadas_2h' => ['Realizadas 2h', 'stethoscope'],
            'canceladas_2h' => ['Canceladas 2h', 'cancel'],
          ];
          $title = $labels[$label][0] ?? $label;
          $icon = $labels[$label][1] ?? 'insights';
        @endphp
        <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $title }}</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ $value }}</p>
            </div>
            <span class="material-symbols-outlined flex h-12 w-12 items-center justify-center rounded-full bg-sky-500/10 text-2xl text-sky-600">
              {{ $icon }}
            </span>
          </div>
        </article>
      @endforeach
    </section>

    <section class="rounded-xl bg-white p-6 shadow">
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-slate-800">Próximas citas</h2>
        <a href="{{ route('doctor.citas') }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">Ver agenda completa</a>
      </div>
      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
          <thead class="bg-slate-100 text-left text-xs uppercase tracking-wide text-slate-600">
            <tr>
              <th class="px-4 py-2">Paciente</th>
              <th class="px-4 py-2">Estado</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Hora</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($agenda as $cita)
              <tr>
                <td class="px-4 py-3 font-medium text-slate-800">{{ $cita['paciente'] }}</td>
                <td class="px-4 py-3 text-slate-700">{{ ucfirst($cita['estado']) }}</td>
                <td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($cita['fecha'])->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($cita['hora'])->format('H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-slate-500">No tienes citas programadas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
