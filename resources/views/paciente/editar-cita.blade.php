@extends('layouts.paciente')
@section('title', 'Reagendar Cita')
@section('body-class', 'paciente-body--editar-cita')
@push('head')
    <style>
        :root{
            --primary:#6b74ff;
            --primary-2:#8ea1ff;
            --danger:#ff6b81;
            --success:#41f1b6;
            --info:#e7ecff;
            --ink:#1f2330;
            --muted:#6b7280;
            --card:#ffffff;
            --ring:rgba(107,116,255,.35);
            --shadow:0 20px 35px rgba(31,35,48,.10), 0 8px 14px rgba(31,35,48,.06);
            --radius-lg:22px;
            --radius-sm:14px;
        }
        body.paciente-body--editar-cita {
            font-family:"Poppins",system-ui,-apple-system,Segoe UI,Roboto,Arial;
            color:var(--ink);
            background:radial-gradient(1200px 500px at 20% -10%, #f1f3ff 0%, #ffffff 55%) fixed;
            position:relative;
        }
        body.paciente-body--editar-cita::before,
        body.paciente-body--editar-cita::after{
            content:"";
            position:fixed;
            inset:auto auto 10% -10%;
            width:420px;
            height:420px;
            border-radius:50%;
            filter:blur(70px);
            opacity:.38;
            z-index:-1;
        }
        body.paciente-body--editar-cita::before{
            background:linear-gradient(120deg, #f3f6ff, #e9faff);
        }
        body.paciente-body--editar-cita::after{
            left:auto;
            right:-8%;
            bottom:15%;
            background:linear-gradient(120deg, #ffe9ef, #ecf0ff);
        }
        body.paciente-body--editar-cita main {
            margin:0;
            padding:0;
            background:transparent;
        }
        body.paciente-body--editar-cita .editar-cita-page{
            max-width:980px;
            margin:48px auto;
            padding:0 20px;
        }
        body.paciente-body--editar-cita .card{
            background:var(--card);
            border-radius:var(--radius-lg);
            box-shadow:var(--shadow);
            overflow:hidden;
            border:1px solid #f2f4ff;
        }
        body.paciente-body--editar-cita .card-header{
            padding:24px 24px 18px;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
        }
        body.paciente-body--editar-cita .header-panel{
            background:#ffffff;
            border-radius:16px;
            padding:18px 20px;
            box-shadow:0 10px 20px rgba(16,24,40,.06);
            display:flex;
            align-items:center;
            gap:12px;
            max-width:92%;
            margin:0 auto;
        }
        body.paciente-body--editar-cita .card-header .title{
            color:#1f2330;
            font-size:1.35rem;
            font-weight:800;
            margin:0 0 4px 0;
        }
        body.paciente-body--editar-cita .card-header .subtitle{
            color:#667085;
            margin:0;
            font-weight:600;
            line-height:1.3;
        }
        body.paciente-body--editar-cita .card-body{
            padding:26px;
        }
        body.paciente-body--editar-cita .meta{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:14px;
            margin-bottom:18px;
        }
        body.paciente-body--editar-cita .meta .kpi{
            background:#f9faff;
            border:1px solid #eef1ff;
            border-radius:14px;
            padding:14px 14px;
        }
        body.paciente-body--editar-cita .kpi .label{
            font-size:.78rem;
            color:var(--muted);
            font-weight:600;
            letter-spacing:.3px;
            text-transform:uppercase;
        }
        body.paciente-body--editar-cita .kpi .value{
            margin-top:6px;
            font-weight:700;
            font-size:1.02rem;
        }
        body.paciente-body--editar-cita .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 10px;
            border-radius:999px;
            font-weight:700;
            font-size:.82rem;
        }
        body.paciente-body--editar-cita .pill svg{
            width:16px;
            height:16px;
        }
        body.paciente-body--editar-cita .pill.pending{background:#fff1f1;color:#d63131;border:1px solid #ffd4d4}
        body.paciente-body--editar-cita .pill.info{background:#eef2ff;color:#3a47d5;border:1px solid #dfe4ff}
        body.paciente-body--editar-cita .pill.success{background:#e9fff6;color:#128462;border:1px solid #c9ffe9}
        body.paciente-body--editar-cita .pill.danger{background:#ffe9ef;color:#a21736;border:1px solid #ffd6e0}
        body.paciente-body--editar-cita .form-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:16px;
            margin-top:8px;
        }
        body.paciente-body--editar-cita .input{
            position:relative;
            display:flex;
            align-items:center;
            background:#ffffff;
            border:1px solid #e7e9f5;
            border-radius:14px;
            padding:10px 12px 10px 40px;
            transition:border .2s, box-shadow .2s, transform .05s;
        }
        body.paciente-body--editar-cita .input:focus-within{
            border-color:var(--primary);
            box-shadow:0 0 0 6px var(--ring);
        }
        body.paciente-body--editar-cita .input .icon{
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            opacity:.7;
        }
        body.paciente-body--editar-cita .input input{
            width:100%;
            border:none;
            outline:none;
            background:transparent;
            font:600 .98rem/1.4 "Poppins",system-ui;
            color:var(--ink);
        }
        body.paciente-body--editar-cita label{
            display:block;
            font-size:.88rem;
            font-weight:700;
            color:#3a3f52;
            margin:10px 0 8px 6px;
        }
        body.paciente-body--editar-cita .help{
            margin-top:6px;
            font-size:.78rem;
            color:var(--muted);
        }
        body.paciente-body--editar-cita .error{
            display:block;
            margin-top:6px;
            color:var(--danger);
            font-size:.83rem;
            font-weight:600;
        }
        body.paciente-body--editar-cita .actions{
            display:flex;
            gap:12px;
            margin-top:22px;
            flex-wrap:wrap;
        }
        body.paciente-body--editar-cita .btn{
            appearance:none;
            border:none;
            cursor:pointer;
            font-weight:800;
            letter-spacing:.2px;
            padding:12px 18px;
            border-radius:14px;
            transition: transform .05s ease, box-shadow .2s ease, filter .2s ease;
        }
        body.paciente-body--editar-cita .btn:active{
            transform:translateY(1px) scale(.995);
        }
        body.paciente-body--editar-cita .btn-primary{
            color:#fff;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow:0 12px 24px rgba(107,116,255,.30);
        }
        body.paciente-body--editar-cita .btn-primary:hover{filter:brightness(1.03)}
        body.paciente-body--editar-cita .btn-ghost{
            background:#f2f5ff;
            color:#2a2f45;
            border:1px solid #e5e9ff;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
        }
        body.paciente-body--editar-cita .btn-ghost:hover{filter:brightness(1.02)}
        body.paciente-body--editar-cita .alert{
            background:var(--danger);
            color:#fff;
            padding:12px 14px;
            border-radius:12px;
            font-weight:600;
            margin-bottom:14px;
        }
        @media (max-width: 900px){
            body.paciente-body--editar-cita .header-panel{max-width:100%;}
        }
        @media (max-width: 780px){
            body.paciente-body--editar-cita .meta{grid-template-columns:1fr;}
            body.paciente-body--editar-cita .form-grid{grid-template-columns:1fr;}
            body.paciente-body--editar-cita .card-header{padding:18px;}
            body.paciente-body--editar-cita .card-body{padding:20px;}
        }
    </style>
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
                                <input id="fecha" type="date" name="fecha" value="{{ old('fecha', $cita->fecha) }}" min="{{ \Carbon\Carbon::now('America/Guayaquil')->toDateString() }}" required>
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
                                <input id="hora" type="time" name="hora" value="{{ old('hora', $cita->hora) }}" required>
                            </div>
                            @error('hora')<span class="error">{{ $message }}</span>@enderror
                            <div class="help">Formato 24 horas.</div>
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
