@extends('layouts.app')

@section('title', 'Panel administrativo')

@section('content')
  <div class="flex flex-col gap-8">
    <header class="flex flex-col gap-2">
      <h1 class="text-3xl font-bold text-slate-900">Bienvenido, {{ $user->name }}</h1>
      <p class="text-slate-600">Resumen operativo de la clínica con métricas financieras y de agenda.</p>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
      @php
        $cards = [
          ['titulo' => 'Citas agendadas', 'icono' => 'event_available', 'valor' => $metrics['agendadas'], 'color' => 'bg-sky-500/10 text-sky-600'],
          ['titulo' => 'Pendientes', 'icono' => 'pending_actions', 'valor' => $metrics['pendientes'], 'color' => 'bg-amber-500/10 text-amber-600'],
          ['titulo' => 'Realizadas', 'icono' => 'task_alt', 'valor' => $metrics['realizadas'], 'color' => 'bg-emerald-500/10 text-emerald-600'],
          ['titulo' => 'Canceladas', 'icono' => 'cancel', 'valor' => $metrics['canceladas'], 'color' => 'bg-rose-500/10 text-rose-600'],
          ['titulo' => 'Ingresos emitidos', 'icono' => 'payments', 'valor' => number_format($metrics['ingresos'], 2).' USD', 'color' => 'bg-indigo-500/10 text-indigo-600'],
        ];
      @endphp

      @foreach($cards as $card)
        <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $card['titulo'] }}</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ $card['valor'] }}</p>
            </div>
            <span class="material-symbols-outlined {{ $card['color'] }} flex h-12 w-12 items-center justify-center rounded-full text-2xl">
              {{ $card['icono'] }}
            </span>
          </div>
        </article>
      @endforeach
    </section>

    <section class="rounded-xl bg-white p-6 shadow">
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-slate-800">Citas recientes</h2>
        <a href="{{ route('admin.citas.export') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
          <span class="material-symbols-outlined text-base">download</span>
          Exportar Excel
        </a>
      </div>
      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
          <thead class="bg-slate-100 text-left text-xs uppercase tracking-wide text-slate-600">
            <tr>
              <th class="px-4 py-2">Paciente</th>
              <th class="px-4 py-2">Doctor</th>
              <th class="px-4 py-2">Estado</th>
              <th class="px-4 py-2">Fecha</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($citas as $cita)
              <tr>
                <td class="px-4 py-3 font-medium text-slate-800">{{ $cita->paciente->name ?? 'Paciente' }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $cita->doctor->name ?? 'Sin asignar' }}</td>
                <td class="px-4 py-3">
                  @php
                    $estadoColor = [
                      'pendiente' => 'bg-amber-500/10 text-amber-600',
                      'realizada' => 'bg-emerald-500/10 text-emerald-600',
                      'cancelada' => 'bg-rose-500/10 text-rose-600',
                      'confirmada'=> 'bg-sky-500/10 text-sky-600',
                    ][$cita->estado] ?? 'bg-slate-100 text-slate-600';
                  @endphp
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $estadoColor }}">
                    {{ ucfirst($cita->estado) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-slate-600">{{ $cita->fecha->format('d/m/Y') }} {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-slate-500">No hay citas registradas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
