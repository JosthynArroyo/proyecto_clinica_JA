@extends('layouts.app')

@section('title', 'Panel de paciente')

@section('content')
  @php
    $estadoBadge = fn (string $estado) => [
      'pendiente' => 'bg-amber-500/10 text-amber-600',
      'realizada' => 'bg-emerald-500/10 text-emerald-600',
      'cancelada' => 'bg-rose-500/10 text-rose-600',
      'confirmada'=> 'bg-sky-500/10 text-sky-600',
    ][$estado] ?? 'bg-slate-100 text-slate-600';
  @endphp

  <div class="flex flex-col gap-8">
    <header class="flex flex-col gap-2">
      <h1 class="text-3xl font-bold text-slate-900">Hola, {{ $user->name }}</h1>
      <p class="text-slate-600">Gestiona tus citas médicas y revisa tu historial.</p>
      <div>
        <a href="{{ route('paciente.crear-cita') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600">
          <span class="material-symbols-outlined text-base">add_circle</span>
          Agendar nueva cita
        </a>
      </div>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total de citas</p>
        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $metrics['total'] }}</p>
      </article>
      <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pendientes</p>
        <p class="mt-2 text-2xl font-bold text-amber-600">{{ $metrics['pendientes'] }}</p>
      </article>
      <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Realizadas</p>
        <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $metrics['realizadas'] }}</p>
      </article>
      <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Canceladas</p>
        <p class="mt-2 text-2xl font-bold text-rose-600">{{ $metrics['canceladas'] }}</p>
      </article>
    </section>

    <section class="rounded-xl bg-white p-6 shadow">
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-slate-800">Próximas citas</h2>
        <a href="{{ route('paciente.citas') }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">Ver todas</a>
      </div>
      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
          <thead class="bg-slate-100 text-left text-xs uppercase tracking-wide text-slate-600">
            <tr>
              <th class="px-4 py-2">Doctor</th>
              <th class="px-4 py-2">Especialidad</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Hora</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($citasProximas as $cita)
              <tr>
                <td class="px-4 py-3 font-medium text-slate-800">{{ $cita->doctor->name ?? 'Sin asignar' }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $cita->especialidad->nombre ?? '—' }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $cita->fecha->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $estadoBadge($cita->estado) }}">
                    {{ ucfirst($cita->estado) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aún no tienes citas próximas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
