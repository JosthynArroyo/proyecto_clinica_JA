@extends('layouts.paciente')
@section('title', 'Mis Citas Médicas')
@section('body-class', 'paciente-body--citas')

@push('head')
<style>
  :root{
    --primary:#6b74ff;
    --primary-2:#8ea1ff;
    --danger:#ff6b81;
    --success:#41f1b6;
    --ink:#1f2330;
    --muted:#6b7280;
    --card:#ffffff;
    --ring:rgba(107,116,255,.35);
    --shadow:0 20px 35px rgba(31,35,48,.10), 0 8px 14px rgba(31,35,48,.06);
    --radius-xl:24px;
    --radius-lg:18px;
    --radius-sm:12px;
  }

  /* ===== Page ===== */
  body.paciente-body--citas{
    font-family:"Poppins",system-ui,-apple-system,Segoe UI,Roboto,Arial;
    color:var(--ink);
    background:radial-gradient(1200px 520px at 20% -12%, #f1f3ff 0%, #ffffff 55%) fixed;
  }
  /* Asegura que el área de contenido use todo el ancho disponible */
  body.paciente-body--citas main{
    margin:0;
    padding:0;
    background:transparent;
    width:100%;
    max-width:none;
    display:block;
  }

  /* Contenedor principal sin límites de ancho */
  .citas{
    width:100%;
    max-width:none;
    margin:0;                 /* sin centrado */
    padding:32px 28px 64px;   /* respiración interna */
    display:grid;
    gap:18px;
    box-sizing:border-box;
  }

  /* ===== Header ===== */
  .hero{
    position:relative;
    border-radius:var(--radius-xl);
    background:linear-gradient(135deg, var(--primary), var(--primary-2));
    padding:24px;
    box-shadow:var(--shadow);
    overflow:hidden;
    isolation:isolate;
  }
  .hero::after{
    content:"";
    position:absolute; inset:auto -10% -40% auto;
    width:420px; height:420px; border-radius:50%;
    background:rgba(255,255,255,.16);
    filter:blur(28px);
    z-index:0;
  }
  .hero-inner{
    position:relative; z-index:1;
    background:#fff;
    border-radius:var(--radius-lg);
    padding:20px;
    box-shadow:0 10px 24px rgba(16,24,40,.08);
    display:grid; grid-template-columns:1fr auto; gap:16px; align-items:center;
  }
  .hgroup{ min-width:0; }
  .title{
    margin:0;
    font-weight:800;
    font-size:clamp(1.25rem,1.1rem + .8vw,1.8rem);
    color:#1f2330;
    letter-spacing:.2px;
  }
  .subtitle{
    margin:6px 0 0;
    color:#667085;
    font-weight:600;
    font-size:.96rem;
  }

  .hero-actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

  /* Buttons */
  .btn{
    appearance:none; border:none; cursor:pointer;
    display:inline-flex; align-items:center; gap:10px;
    text-decoration:none; font-weight:800; letter-spacing:.2px;
    padding:12px 16px; border-radius:14px;
    transition:transform .06s ease, filter .2s ease, box-shadow .2s ease;
    outline:none;
  }
  .btn:active{ transform:translateY(1px) scale(.996); }
  .btn:focus-visible{ box-shadow:0 0 0 4px var(--ring); }

  .btn-primary{
    color:#fff;
    background:linear-gradient(135deg, var(--primary), var(--primary-2));
    box-shadow:0 14px 28px rgba(107,116,255,.28);
  }
  .btn-outline{
    color:#3a47d5;
    background:#fff;
    border:1px solid #dee6ff;
  }
  .btn-danger{
    color:#fff; background:var(--danger);
    box-shadow:0 10px 22px rgba(255,119,130,.28);
  }

  /* ===== Toolbar (search + filters) ===== */
  .toolbar{
    display:grid;
    grid-template-columns:1fr; /* la barra ocupa ancho completo */
    gap:12px;
    align-items:center;
    margin-top:4px;
  }
  .filters{
    background:#fff;
    border:1px solid #e9eeff;
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow);
    padding:12px;
    display:grid;
    grid-template-columns:1fr 200px 140px; /* más ancha por el nuevo layout fluido */
    gap:10px;
  }
  .field{
    position:relative;
    display:flex; align-items:center;
    background:#fff;
    border:1px solid #e7ebff;
    border-radius:12px;
    padding:8px 12px;
  }
  .field:focus-within{ box-shadow:0 0 0 4px var(--ring); }
  .field input, .field select{
    width:100%; border:none; outline:none; font:inherit; color:var(--ink); background:transparent;
  }
  .field input::placeholder{ color:#98a2b3; }
  .toolbar .btn{ height:44px; justify-content:center; }

  /* ===== List ===== */
  .list{ display:grid; gap:14px; }

  .card{
    background:var(--card);
    border:1px solid #e9eeff;
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow);
    padding:16px 18px;
    display:grid; grid-template-columns: 1fr auto; gap:14px; align-items:center;
    transition:box-shadow .15s ease;
  }
  .card:hover{ box-shadow:0 18px 30px rgba(31,35,48,.12); }
  .card:focus-within{ box-shadow:0 0 0 4px var(--ring); }

  .left{ display:grid; grid-template-columns:auto 1fr; gap:14px; align-items:center; min-width:0; }
  .avatar{
    width:48px; height:48px; border-radius:50%;
    background:linear-gradient(135deg,#eef2ff,#ffffff);
    border:1px solid #e5eaff;
    display:grid; place-items:center; font-weight:800; color:#3a47d5;
  }

  .title-row{
    margin:0 0 6px 0; font-weight:800; color:#24283a; font-size:1.05rem;
    display:flex; flex-wrap:wrap; gap:10px; align-items:center;
  }
  .chip{
    display:inline-flex; align-items:center; gap:8px;
    padding:6px 10px; border-radius:999px; font-weight:800; font-size:.8rem;
    border:1px solid #dee6ff; background:#f3f5ff; color:#3a47d5; white-space:nowrap;
  }
  .meta{ margin:0; color:#60657a; font-weight:600; font-size:.94rem; }
  .meta + .meta{ margin-top:4px; }
  .meta strong{ color:#3b4158; font-weight:800; }

  .right{ display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
  .pill{
    display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px;
    font-weight:800; font-size:.78rem; border:1px solid transparent; white-space:nowrap;
  }
  .pill.pending{ background:#fff6d8; color:#8a6d3b; border-color:#ffe9a8; }
  .pill.info{ background:#eef2ff; color:#3a47d5; border-color:#dee6ff; }
  .pill.danger{ background:#ffe9ef; color:#a21736; border-color:#ffd6e0; }
  .pill.success{ background:#e9fff6; color:#128462; border-color:#c9ffe9; }

  .actions{ display:flex; gap:10px; flex-wrap:wrap; }

  /* ===== Empty ===== */
  .empty{
    background:#fff; border:1px solid #e9eeff; border-radius:var(--radius-lg);
    padding:28px; text-align:center; color:#60657a; font-weight:600; box-shadow:var(--shadow);
  }
  .empty h3{ margin:0 0 6px; color:#24283a; font-weight:800; }
  .empty p{ margin:0 0 14px; }

  /* ===== Responsive ===== */
  @media (max-width:1200px){
    .filters{ grid-template-columns:1fr 180px 120px; }
  }
  @media (max-width:1024px){
    .hero-inner{ grid-template-columns:1fr; }
    .hero-actions{ justify-content:flex-start; }
  }
  @media (max-width:860px){
    .filters{ grid-template-columns:1fr 1fr auto; }
  }
  @media (max-width:720px){
    .filters{ grid-template-columns:1fr; }
    .toolbar{ grid-template-columns:1fr; }
    .card{ grid-template-columns:1fr; }
    .right{ align-items:flex-start; }
    .actions{ width:100%; }
    .actions .btn{ flex:1; justify-content:center; }
    .citas{ padding:24px 16px 48px; }
  }
</style>
@endpush

@section('main')
<div class="citas">
  {{-- Hero --}}
  <section class="hero" aria-labelledby="citas-heading">
    <div class="hero-inner">
      <div class="hgroup">
        <h1 id="citas-heading" class="title">Mis Citas Médicas</h1>
        <p class="subtitle">Gestiona, busca y filtra tus citas. Todo en un solo lugar.</p>
      </div>
      <div class="hero-actions">
        <a href="{{ route('paciente.crear-cita') }}" class="btn btn-primary" aria-label="Agendar nueva cita">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Agendar Cita
        </a>
      </div>
    </div>
  </section>

  {{-- Toolbar (search + filters) --}}
  <section class="toolbar" aria-label="Barra de búsqueda y filtros">
    <form class="filters" method="GET" action="{{ url()->current() }}">
      <div class="field" role="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="margin-right:8px">
          <circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3" stroke-linecap="round"/>
        </svg>
        <input
          type="text"
          name="q"
          value="{{ request('q') }}"
          placeholder="Buscar por doctor o especialidad…"
          aria-label="Buscar citas"
        />
      </div>

      <div class="field">
        @php $est = request('estado'); @endphp
        <select name="estado" aria-label="Filtrar por estado">
          <option value="">Todos los estados</option>
          <option value="pendiente"  {{ $est==='pendiente' ? 'selected' : '' }}>Pendiente</option>
          <option value="confirmada" {{ $est==='confirmada' ? 'selected' : '' }}>Confirmada</option>
          <option value="cancelada"  {{ $est==='cancelada' ? 'selected' : '' }}>Cancelada</option>
          <option value="realizada"  {{ $est==='realizada' ? 'selected' : '' }}>Realizada</option>
        </select>
      </div>

      <button class="btn btn-outline" type="submit" aria-label="Aplicar filtros">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Filtrar
      </button>
    </form>
  </section>

  {{-- Alerts --}}
  @if(session('success') || session('error'))
    <div aria-live="polite" aria-atomic="true">
      @if(session('success'))
        <div class="empty" role="status" style="border-color:#c7f1de; background:#dff7ea; color:#137a5a">
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="empty" role="alert" style="border-color:#ffd6df; background:#ffe8ec; color:#a21736">
          {{ session('error') }}
        </div>
      @endif
    </div>
  @endif

  {{-- Listado --}}
  <section class="list" aria-label="Listado de citas">
    @php
      $collection = $citas ?? collect();
    @endphp

    @if($collection->isEmpty())
      <div class="empty">
        <h3>No tienes citas registradas</h3>
        <p>Agenda tu primera cita para verla aquí con su estado y acciones.</p>
        <a href="{{ route('paciente.crear-cita') }}" class="btn btn-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Agendar Cita
        </a>
      </div>
    @else
      @foreach($collection as $cita)
        <article class="card">
          <div class="left">
            <div class="avatar" aria-hidden="true">
              @php
                $name = $cita->doctor->name ?? 'DR';
                $ini = mb_substr(trim($name),0,2,'UTF-8');
              @endphp
              {{ mb_strtoupper($ini,'UTF-8') }}
            </div>
            <div class="info">
              <h3 class="title-row">
                <span class="chip" aria-label="Fecha y hora">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                  •
                  {{ strlen($cita->hora ?? '')>=5 ? substr($cita->hora,0,5) : $cita->hora }}
                </span>
              </h3>
              <p class="meta"><strong>Doctor:</strong> {{ $cita->doctor->name ?? 'Sin asignar' }}</p>
              <p class="meta"><strong>Especialidad:</strong> {{ $cita->especialidad->nombre ?? 'Sin especialidad' }}</p>
            </div>
          </div>

          <div class="right">
            @switch($cita->estado)
              @case('pendiente')  <span class="pill pending">PENDIENTE</span>  @break
              @case('confirmada') <span class="pill info">CONFIRMADA</span>   @break
              @case('cancelada')  <span class="pill danger">CANCELADA</span>   @break
              @case('realizada')  <span class="pill success">REALIZADA</span>  @break
              @default            <span class="pill info">{{ strtoupper($cita->estado) }}</span>
            @endswitch

            @if(!in_array($cita->estado, ['cancelada','realizada']))
              <div class="actions">
                <form action="{{ route('paciente.citas.cancelar', $cita->id) }}" method="POST" style="display:inline-block">
                  @csrf
                  <button type="submit" class="btn btn-danger" aria-label="Cancelar cita">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Cancelar
                  </button>
                </form>
                <a class="btn btn-outline" href="{{ route('paciente.editar-cita', $cita->id) }}" aria-label="Reagendar cita">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 12a9 9 0 1 0 9-9M3 3v6h6" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  Reagendar
                </a>
              </div>
            @endif
          </div>
        </article>
      @endforeach
    @endif
  </section>

  {{-- Paginación (opcional si $citas es LengthAwarePaginator) --}}
  @if(method_exists($citas, 'links'))
    <div style="display:flex;justify-content:center;margin-top:10px;">
      {{ $citas->appends(['q'=>request('q'),'estado'=>request('estado')])->links() }}
    </div>
  @endif
</div>
@endsection
