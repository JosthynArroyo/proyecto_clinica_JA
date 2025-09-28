@extends('layouts.paciente')
@section('title', 'Mis Citas Médicas')
@section('body-class', 'paciente-body--citas')
@push('head')
    <style>
        :root {
            --primary:#6b74ff;
            --primary-2:#8ea1ff;
            --danger:#ff6b81;
            --success:#41f1b6;
            --ink:#1f2330;
            --muted:#6b7280;
            --card:#ffffff;
            --ring:rgba(107,116,255,.35);
            --shadow:0 20px 35px rgba(31,35,48,.10), 0 8px 14px rgba(31,35,48,.06);
            --radius-lg:22px;
            --radius-sm:14px;
        }
        body.paciente-body--citas {
            font-family:"Poppins",system-ui,-apple-system,Segoe UI,Roboto,Arial;
            color:var(--ink);
            background:radial-gradient(1200px 500px at 20% -10%, #f1f3ff 0%, #ffffff 55%) fixed;
        }
        body.paciente-body--citas main {
            margin:0;
            padding:0;
            background:transparent;
        }
        body.paciente-body--citas .citas-page {
            max-width:980px;
            margin:42px auto;
            padding:0 20px;
        }
        body.paciente-body--citas .page-header {
            padding:24px 24px 18px;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            border-radius:22px;
            box-shadow:var(--shadow);
        }
        body.paciente-body--citas .header-panel {
            background:#ffffff;
            border-radius:16px;
            padding:18px 20px;
            box-shadow:0 10px 20px rgba(16,24,40,.06);
            display:flex;
            align-items:center;
            gap:14px;
            justify-content:space-between;
        }
        body.paciente-body--citas .header-left {
            display:flex;
            align-items:center;
            gap:14px;
        }
        body.paciente-body--citas .title {
            margin:0;
            font-size:1.6rem;
            font-weight:800;
            color:#1f2330;
        }
        body.paciente-body--citas .subtitle {
            margin:2px 0 0;
            color:#667085;
            font-weight:600;
            font-size:.95rem;
        }
        body.paciente-body--citas .btn-back {
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            color:#fff;
            text-decoration:none;
            font-weight:800;
            letter-spacing:.2px;
            padding:10px 14px;
            border-radius:12px;
            display:inline-flex;
            align-items:center;
            gap:8px;
            box-shadow:0 10px 18px rgba(107,116,255,.25);
            border:none;
            cursor:pointer;
        }
        body.paciente-body--citas .alerts {
            margin:18px 4px 4px;
        }
        body.paciente-body--citas .alert {
            border-radius:12px;
            padding:12px 14px;
            font-weight:600;
            margin:10px 0;
        }
        body.paciente-body--citas .alert-success {
            background:#dff7ea;
            color:#137a5a;
            border:1px solid #c7f1de;
        }
        body.paciente-body--citas .alert-danger {
            background:#ffe8ec;
            color:#a21736;
            border:1px solid #ffd6df;
        }
        body.paciente-body--citas .list {
            margin-top:18px;
            display:grid;
            gap:18px;
        }
        body.paciente-body--citas .appt {
            background:#fff;
            border:1px solid #eef1ff;
            border-radius:var(--radius-lg);
            box-shadow:var(--shadow);
            padding:18px 20px;
            display:grid;
            grid-template-columns:1fr auto;
            gap:14px;
            align-items:center;
        }
        body.paciente-body--citas .appt:hover {
            box-shadow:0 16px 28px rgba(31,35,48,.12);
        }
        body.paciente-body--citas .appt-title {
            margin:0 0 6px 0;
            font-weight:800;
            color:#24283a;
            font-size:1.05rem;
        }
        body.paciente-body--citas .appt-meta {
            margin:0;
            color:#60657a;
            font-weight:600;
            font-size:.93rem;
        }
        body.paciente-body--citas .appt-meta + .appt-meta {
            margin-top:6px;
        }
        body.paciente-body--citas .right {
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:10px;
        }
        body.paciente-body--citas .pill {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 10px;
            border-radius:999px;
            font-weight:800;
            font-size:.8rem;
            border:1px solid transparent;
        }
        body.paciente-body--citas .pill.pending {
            background:#fff6d8;
            color:#8a6d3b;
            border-color:#ffe9a8;
        }
        body.paciente-body--citas .pill.info {
            background:#eef2ff;
            color:#3a47d5;
            border-color:#dee6ff;
        }
        body.paciente-body--citas .pill.danger {
            background:#ffe9ef;
            color:#a21736;
            border-color:#ffd6e0;
        }
        body.paciente-body--citas .pill.success {
            background:#e9fff6;
            color:#128462;
            border-color:#c9ffe9;
        }
        body.paciente-body--citas .actions {
            display:flex;
            gap:10px;
        }
        body.paciente-body--citas .btn {
            appearance:none;
            border:none;
            cursor:pointer;
            font-weight:800;
            letter-spacing:.2px;
            padding:10px 14px;
            border-radius:12px;
            transition: transform .05s ease, filter .2s ease;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
        }
        body.paciente-body--citas .btn:active {
            transform:translateY(1px) scale(.995);
        }
        body.paciente-body--citas .btn-cancel {
            background:#ff7782;
            color:#fff;
            box-shadow:0 10px 18px rgba(255,119,130,.25);
        }
        body.paciente-body--citas .btn-cancel:hover {
            filter:brightness(1.05);
        }
        body.paciente-body--citas .btn-primary {
            color:#fff;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow:0 12px 24px rgba(107,116,255,.30);
        }
        body.paciente-body--citas .btn-primary:hover {
            filter:brightness(1.03);
        }
        body.paciente-body--citas .empty {
            background:#fff;
            border:1px solid #eef1ff;
            border-radius:18px;
            padding:24px;
            text-align:center;
            color:#60657a;
            font-weight:600;
        }
        @media (max-width:780px) {
            body.paciente-body--citas .header-panel {
                flex-direction:column;
                align-items:flex-start;
                gap:10px;
            }
            body.paciente-body--citas .right {
                align-items:flex-start;
            }
            body.paciente-body--citas .appt {
                grid-template-columns:1fr;
            }
            body.paciente-body--citas .actions {
                width:100%;
            }
            body.paciente-body--citas .actions .btn {
                flex:1;
                justify-content:center;
            }
        }
    </style>
@endpush
@section('main')
    <div class="citas-page">
        <div class="page-header">
            <div class="header-panel">
                <div class="header-left">
                    <a href="{{ route('paciente.dashboard') }}" class="btn-back" aria-label="Regresar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6"/>
                        </svg>
                        Regresar
                    </a>
                    <div>
                        <h1 class="title">Mis Citas Médicas</h1>
                        <p class="subtitle">Consulta, cancela o reagenda tus próximas citas.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alerts">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        <div class="list">
            @if($citas->isEmpty())
                <div class="empty">No tienes citas registradas.</div>
            @else
                @foreach($citas as $cita)
                    <div class="appt">
                        <div class="left">
                            <h3 class="appt-title">
                                Fecha: {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                &nbsp;|&nbsp;
                                Hora: {{ strlen($cita->hora ?? '')>=5 ? substr($cita->hora,0,5) : $cita->hora }}
                            </h3>
                            <p class="appt-meta">Doctor: {{ $cita->doctor->name ?? 'Sin asignar' }}</p>
                            <p class="appt-meta">Especialidad: {{ $cita->especialidad->nombre ?? 'Sin especialidad' }}</p>
                        </div>
                        <div class="right">
                            @switch($cita->estado)
                                @case('pendiente')
                                    <span class="pill pending">PENDIENTE</span>
                                    @break
                                @case('confirmada')
                                    <span class="pill info">CONFIRMADA</span>
                                    @break
                                @case('cancelada')
                                    <span class="pill danger">CANCELADA</span>
                                    @break
                                @case('realizada')
                                    <span class="pill success">REALIZADA</span>
                                    @break
                                @default
                                    <span class="pill info">{{ strtoupper($cita->estado) }}</span>
                            @endswitch
                            @if(!in_array($cita->estado, ['cancelada','realizada']))
                                <div class="actions">
                                    <form action="{{ route('paciente.citas.cancelar', $cita->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-cancel">Cancelar</button>
                                    </form>
                                    <a class="btn btn-primary" href="{{ route('paciente.editar-cita', $cita->id) }}">Reagendar</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection