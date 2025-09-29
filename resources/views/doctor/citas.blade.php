@extends('layouts.doctor')
@section('title', 'Mis Citas (Doctor)')
@section('activeSidebar', 'citas')

@push('styles')
<style>
  /* ======= WRAPPER ======= */
  .appointments-page{
    width:min(1100px,100%);
    margin:84px auto 36px;
    padding:0 clamp(16px,2.4vw,28px) 36px;
  }

  /* ======= HEADER / ACTIONS ======= */
  .appointments-card{
    background:var(--clr-white);
    border:1px solid var(--clr-info-light);
    border-radius:var(--card-border-radius);
    box-shadow:var(--box-shadow);
    overflow:hidden;
  }
  .appointments-header{
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:16px 18px;
    background:linear-gradient(180deg,#fafbff 0%, #ffffff 78%);
    border-bottom:1px solid #e7edf7;
  }
  .appointments-title{
    display:flex; align-items:center; gap:.7rem;
  }
  .appointments-title h1{
    margin:0; font-size:1.55rem; font-weight:800; color:var(--clr-dark);
    letter-spacing:.2px;
  }
  .actions-right{display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;}

  .btn{
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.52rem .9rem; border-radius:10px; font-weight:700;
    border:1px solid transparent; text-decoration:none; cursor:pointer;
    transition:transform .05s, box-shadow .2s, filter .2s, background .2s;
    font-size:.92rem;
  }
  .btn:active{ transform:translateY(1px) scale(.995); }
  .btn-primary{ background:var(--clr-primary); color:#fff; }
  .btn-primary:hover{ filter:brightness(.95); }
  .btn-soft{
    background:#f5f7ff; border-color:#e6ebff; color:#3b4b9a;
  }
  .btn-soft:hover{ background:#eef2ff; }

  .toolbar{
    display:flex; align-items:center; gap:.6rem; flex-wrap:wrap;
    padding:12px 16px; border-bottom:1px solid #eef2fb;
  }
  .field{
    position:relative; display:flex; align-items:center; gap:.4rem;
    border:1px solid #e5eaf5; background:#fff; border-radius:10px; padding:.4rem .6rem;
  }
  .field input[type="search"]{
    border:none; outline:none; min-width:220px; font-weight:600; color:#374151;
    background:transparent; padding:.2rem .2rem;
  }
  .field .material-symbols-outlined{font-size:20px; color:#64748b;}
  .select{
    border:1px solid #e5eaf5; background:#fff; border-radius:10px; height:38px;
    padding:0 .6rem; font-weight:700; color:#374151;
  }

  /* ======= ALERTS ======= */
  .alerts{ padding:14px 16px; }
  .alert{
    padding:.8rem 1rem; border-radius:12px; font-weight:700; border:1px solid transparent;
    display:flex; align-items:center; gap:.55rem;
  }
  .alert-success{ background:#e9f9ef; color:#0b7a4b; border-color:#b8efcf; }
  .alert-danger{ background:#fff1f2; color:#b42318; border-color:#ffd7da; }

  /* ======= TABLE ======= */
  .table-wrap{ overflow:auto; }
  .appointments-table{
    width:100%; border-collapse:separate; border-spacing:0;
  }
  .appointments-table thead th{
    position:sticky; top:0; z-index:1;
    background:var(--clr-primary); color:#fff; text-align:left;
    font-weight:800; letter-spacing:.3px;
    padding:12px 10px; font-size:.92rem;
  }
  .appointments-table tbody td{
    background:#fff; border-bottom:1px solid #eef2fb;
    padding:12px 10px; color:#111827; font-weight:600;
  }
  .appointments-table tbody tr:hover td{
    background:#fbfcff;
  }
  .cell-actions{ display:flex; align-items:center; gap:.45rem; flex-wrap:wrap; }

  .btn-outline-success{
    background:#f0fff8; border:1px solid var(--clr-success); color:var(--clr-success);
  }
  .btn-outline-success:hover{ background:var(--clr-success); color:#0f172a; }

  .btn-outline-danger{
    background:#fff7f7; border:1px solid var(--clr-danger); color:var(--clr-danger);
  }
  .btn-outline-danger:hover{ background:var(--clr-danger); color:#fff; }

  .btn-success{
    background:var(--clr-success); color:#0f172a; border:1px solid rgba(0,0,0,.04);
  }
  .btn-success:hover{ filter:brightness(.96); }

  /* ======= BADGES ======= */
  .badge{
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.28rem .55rem; border-radius:999px; font-weight:800; font-size:.82rem;
    border:1px solid transparent;
  }
  .badge .dot{
    width:8px; height:8px; border-radius:999px; display:inline-block;
  }
  .bg-warning{ background:#fff7e6; color:#7a4e00; border-color:#ffe9c7; }
  .bg-warning .dot{ background:#f59e0b; }
  .bg-info{ background:#ecf2ff; color:#283a9b; border-color:#dce6ff; }
  .bg-info .dot{ background:#3b82f6; }
  .bg-danger{ background:#ffe8ea; color:#9b1c1c; border-color:#ffd0d6; }
  .bg-danger .dot{ background:#ef4444; }
  .bg-success{ background:#e6fff3; color:#0b5e3b; border-color:#c7f7e1; }
  .bg-success .dot{ background:#10b981; }
  .bg-secondary{ background:#f2f4f7; color:#344054; border-color:#e4e7ec; }
  .bg-secondary .dot{ background:#64748b; }

  /* ======= EMPTY STATE ======= */
  .empty{
    padding:28px; text-align:center; color:#475569; font-weight:700;
  }

  /* ======= RESPONSIVE ======= */
  @media (max-width: 860px){
    .appointments-title h1{ font-size:1.25rem; }
    .field input[type="search"]{ min-width:160px; }
    .appointments-table thead th, .appointments-table tbody td{ font-size:.88rem; }
  }
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
      <div class="actions-right">
        <button id="btn-refresh" class="btn btn-primary" type="button">
          <span class="material-symbols-outlined">refresh</span> Actualizar
        </button>
      </div>
    </div>

    <div class="toolbar">
      <div class="field">
        <span class="material-symbols-outlined">search</span>
        <input id="search" type="search" placeholder="Buscar por paciente o especialidad...">
      </div>
      <select id="filter-estado" class="select" aria-label="Filtrar por estado">
        <option value="">Todos los estados</option>
        <option value="pendiente">Pendiente</option>
        <option value="confirmada">Confirmada</option>
        <option value="cancelada">Cancelada</option>
        <option value="realizada">Realizada</option>
      </select>
    </div>

    @if(session('success'))
      <div class="alerts"><div class="alert alert-success">
        <span class="material-symbols-outlined">task_alt</span>{{ session('success') }}
      </div></div>
    @endif
    @if(session('error'))
      <div class="alerts"><div class="alert alert-danger">
        <span class="material-symbols-outlined">error</span>{{ session('error') }}
      </div></div>
    @endif

    @if($citas->isEmpty())
      <div class="empty">No tienes citas asignadas.</div>
    @else
      <div class="table-wrap">
        <table class="appointments-table" id="tabla-citas">
          <thead>
            <tr>
              <th>Paciente</th>
              <th>Especialidad</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th style="min-width:280px">Acciones</th>
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
              <tr data-estado="{{ $estado }}"
                  data-paciente="{{ Str::lower($cita->paciente->name ?? '') }}"
                  data-especialidad="{{ Str::lower($cita->especialidad->nombre ?? '') }}">
                <td>{{ $cita->paciente->name ?? '—' }}</td>
                <td>{{ $cita->especialidad->nombre ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
                <td>
                  <span class="badge {{ $badge }}">
                    <span class="dot"></span>
                    {{ ucfirst($estado) }}
                  </span>
                </td>
                <td class="cell-actions">
                  @if($estado === 'pendiente')
                    <form action="{{ route('doctor.citas.aceptar', $cita->id) }}" method="POST">
                      @csrf
                      <button class="btn btn-outline-success" type="submit">
                        <span class="material-symbols-outlined">done</span> Aceptar
                      </button>
                    </form>
                    <form action="{{ route('doctor.citas.rechazar', $cita->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas rechazar esta cita?');">
                      @csrf
                      <button class="btn btn-outline-danger" type="submit">
                        <span class="material-symbols-outlined">close</span> Rechazar
                      </button>
                    </form>
                  @endif
                  {{-- Corrección: usar ruta de paciente para reagendar --}}
                  <a class="btn btn-success" href="{{ route('paciente.editar-cita', $cita->id) }}">
                    <span class="material-symbols-outlined">event</span> Reagendar
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function(){
    const $search = document.getElementById('search');
    const $filter = document.getElementById('filter-estado');
    const $table = document.getElementById('tabla-citas');
    const $rows = $table ? Array.from($table.querySelectorAll('tbody tr')) : [];

    function norm(s){ return (s||'').toString().trim().toLowerCase(); }

    function applyFilters(){
      const q = norm($search?.value);
      const estado = norm($filter?.value);
      let visible = 0;

      $rows.forEach(tr=>{
        const e = norm(tr.dataset.estado);
        const p = norm(tr.dataset.paciente);
        const esp = norm(tr.dataset.especialidad);

        const matchesEstado = !estado || e === estado;
        const matchesText = !q || p.includes(q) || esp.includes(q);

        if(matchesEstado && matchesText){
          tr.style.display = '';
          visible++;
        }else{
          tr.style.display = 'none';
        }
      });
    }

    $search && $search.addEventListener('input', applyFilters);
    $filter && $filter.addEventListener('change', applyFilters);
    applyFilters();

    const $refresh = document.getElementById('btn-refresh');
    $refresh && $refresh.addEventListener('click', ()=>{ location.reload(); });
  })();
</script>
@endpush
