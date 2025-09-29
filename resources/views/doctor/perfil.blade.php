@extends('layouts.doctor')
@section('title', 'Perfil del Doctor')
@section('activeSidebar', 'perfil')
@section('body-class', 'profile-body')
@push('head')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
@endpush
@push('styles')
    <style>
        body.profile-body {
            background: radial-gradient(1600px 520px at 65% -20%, #e0f2fe 0%, #ffffff 60%);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
            color: #0f172a;
        }
        .profile-wrapper {
            max-width: 1100px;
            margin: 2.8rem auto 3.2rem;
            padding: 0 24px;
            display: grid;
            gap: 24px;
        }
        .profile-hero {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            padding: 28px 32px;
            border-radius: 28px;
            background: linear-gradient(120deg, rgba(14,165,233,.16), rgba(59,130,246,.18));
            border: 1px solid rgba(14,165,233,.28);
            box-shadow: 0 28px 44px rgba(15,23,42,.08);
        }
        .profile-hero h1 {
            margin: 0;
            font-size: 2.15rem;
            font-weight: 800;
        }
        .profile-hero p {
            margin: .5rem 0 0;
            max-width: 42ch;
            color: #1e3a8a;
            font-weight: 500;
        }
        .profile-hero .badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.1rem;
            border-radius: 999px;
            background: rgba(14,165,233,.18);
            border: 1px solid rgba(14,165,233,.3);
            font-weight: 700;
            color: #0f172a;
        }
        .profile-hero .badge .material-symbols-outlined {
            font-variation-settings: 'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;
            color: #0ea5e9;
        }

        .profile-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 26px;
        }
        .profile-aside {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #cfe5fb;
            box-shadow: 0 24px 40px rgba(15,23,42,.08);
            padding: 28px;
            display: grid;
            gap: 20px;
            align-content: start;
        }
        .profile-avatar {
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 999px;
            margin: 0 auto;
            overflow: hidden;
            border: 4px solid #dbeafe;
            box-shadow: 0 14px 22px rgba(14, 116, 190, .16);
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .profile-avatar__overlay {
            position: absolute;
            inset: auto 0 0;
            height: 48px;
            background: linear-gradient(180deg, transparent, rgba(15,23,42,.75));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity .2s;
        }
        .profile-avatar:hover .profile-avatar__overlay {
            opacity: 1;
        }
        .profile-aside h2 {
            margin: 0;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 800;
        }
        .profile-aside span {
            text-align: center;
            color: #475569;
            font-weight: 600;
        }
        .profile-aside .chips {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            justify-content: center;
        }
        .chip {
            background: rgba(14,165,233,.16);
            border-radius: 999px;
            padding: .2rem .7rem;
            color: #0f172a;
            font-weight: 700;
            font-size: .78rem;
            border: 1px solid rgba(14,165,233,.28);
        }
        .profile-aside .summary {
            display: grid;
            gap: .45rem;
        }
        .summary-item {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .55rem .75rem;
            border-radius: 14px;
            background: rgba(14,165,233,.12);
            color: #0f172a;
            font-weight: 600;
            font-size: .9rem;
        }
        .summary-item .material-symbols-outlined {
            color: #0284c7;
            font-variation-settings: 'FILL' 1,'wght' 500,'GRAD' 0,'opsz' 24;
        }

        .profile-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #cfe5fb;
            box-shadow: 0 28px 44px rgba(15,23,42,.08);
            overflow: hidden;
        }
        .profile-card__header {
            padding: 1.6rem 2.2rem;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 80%);
            border-bottom: 1px solid #cfe5fb;
        }
        .profile-card__header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
        }
        .profile-card__header span {
            color: #64748b;
            font-size: .95rem;
        }
        .profile-card__body {
            padding: 2.2rem;
            display: grid;
            gap: 2rem;
        }
        .section {
            border: 1px dashed #bae6fd;
            border-radius: 20px;
            padding: 1.6rem;
            background: #f7fbff;
        }
        .section-title {
            margin: 0 0 1.05rem;
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .6rem;
            color: #0f172a;
        }
        .section-title .material-symbols-outlined {
            color: #0ea5e9;
            font-variation-settings: 'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
            gap: 1.2rem 1.4rem;
        }
        label {
            display: block;
            font-weight: 700;
            margin-bottom: .45rem;
            color: #0f172a;
        }
        input,
        select {
            width: 100%;
            height: 50px;
            border-radius: 14px;
            border: 1.5px solid #bae6fd;
            background: #fff;
            padding: 0 1.1rem;
            font-weight: 600;
            color: #0f172a;
            transition: border .15s, box-shadow .15s;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 6px rgba(14,165,233,.18);
        }
        .help {
            font-size: .82rem;
            color: #64748b;
            margin-top: .35rem;
        }
        .error {
            font-size: .85rem;
            color: #ef4444;
            margin-top: .35rem;
            font-weight: 600;
        }

        .alerts {
            display: grid;
            gap: 1rem;
            margin: 0 2.2rem;
        }
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 18px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .alert.success {
            background: #ecfdf5;
            border-color: #bbf7d0;
            color: #047857;
        }
        .alert.danger {
            background: #fff0f2;
            border-color: #fecdd3;
            color: #9f1239;
        }
        .profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            padding: 0 2.2rem 2.2rem;
            flex-wrap: wrap;
        }
        .btn {
            border: none;
            border-radius: 14px;
            padding: .85rem 1.4rem;
            font-weight: 800;
            cursor: pointer;
            font-size: .95rem;
            transition: transform .05s, filter .2s, box-shadow .2s;
        }
        .btn:active {
            transform: translateY(1px) scale(.995);
        }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            box-shadow: 0 18px 28px rgba(14,165,233,.25);
        }
        .btn-secondary {
            background: #f0f9ff;
            color: #0f172a;
            border: 1px solid #bae6fd;
        }
        .hidden { display: none; }

        @media (max-width: 980px) {
            .profile-layout { grid-template-columns: 1fr; }
            .profile-aside { order: 2; }
        }
    </style>
@endpush

@section('content')
    <div class="profile-wrapper">
        <section class="profile-hero">
            <div>
                <h1>Perfil del doctor</h1>
                <p>Actualiza tu información profesional y de contacto para mantener informados a tus pacientes.</p>
            </div>
            <span class="badge"><span class="material-symbols-outlined">workspace_premium</span>Especialista activo</span>
        </section>

        <div class="profile-layout">
            <aside class="profile-aside">
                <div class="profile-avatar">
                    <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('img/doctor1.jpg') }}" alt="Avatar">
                    <div class="profile-avatar__overlay" id="changePhoto">Cambiar foto</div>
                </div>
                <div>
                    <h2>{{ $user->name }}</h2>
                    <span>{{ $user->email }}</span>
                </div>
                @php
                    $especialidades = ($user->especialidades ?? collect())->pluck('nombre')->all();
                @endphp
                @if(count($especialidades))
                    <div class="chips">
                        @foreach($especialidades as $esp)
                            <span class="chip">{{ $esp }}</span>
                        @endforeach
                    </div>
                @endif
                <div class="summary">
                    @if($user->telefono)
                        <div class="summary-item"><span class="material-symbols-outlined">call</span>{{ $user->telefono }}</div>
                    @endif
                    @if($user->direccion)
                        <div class="summary-item"><span class="material-symbols-outlined">location_on</span>{{ $user->direccion }}</div>
                    @endif
                </div>
            </aside>

            <section class="profile-card">
                <div class="profile-card__header">
                    <h3>Datos personales y profesionales</h3>
                    <span>Todos los campos pueden editarse en cualquier momento.</span>
                </div>

                @if(session('success'))
                    <div class="alerts"><div class="alert success">{{ session('success') }}</div></div>
                @endif
                @if ($errors->any())
                    <div class="alerts">
                        <div class="alert danger">
                            <ul style="margin: 0 0 0 18px; padding: 0;">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('doctor.perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input id="avatarInput" class="hidden" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp">

                    <div class="profile-card__body">
                        <section class="section">
                            <h4 class="section-title"><span class="material-symbols-outlined">badge</span>Identidad</h4>
                            <div class="profile-grid">
                                <div>
                                    <label>Nombre</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Correo</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Teléfono</label>
                                    <input type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                                           inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10"
                                           placeholder="0998740927" title="Debe contener exactamente 10 dígitos">
                                    <div class="help">Formato: 10 dígitos.</div>
                                    @error('telefono')<div class="error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Número de Cédula</label>
                                    <input type="text" name="dni" value="{{ old('dni', $user->dni) }}"
                                           inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10"
                                           placeholder="1723456789" title="Debe contener exactamente 10 dígitos">
                                    <div class="help">Exactamente 10 dígitos.</div>
                                    @error('dni')<div class="error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </section>

                        <section class="section">
                            <h4 class="section-title"><span class="material-symbols-outlined">home_pin</span>Información adicional</h4>
                            <div class="profile-grid">
                                <div>
                                    <label>Dirección</label>
                                    <input type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}">
                                    @error('direccion')<div class="error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Fecha de nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->toDateString()) }}">
                                    @error('fecha_nacimiento')<div class="error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Sexo</label>
                                    <select name="sexo">
                                        <option value="">Seleccionar</option>
                                        <option value="Masculino" {{ old('sexo', $user->sexo)=='Masculino'?'selected':'' }}>Masculino</option>
                                        <option value="Femenino"  {{ old('sexo', $user->sexo)=='Femenino'?'selected':'' }}>Femenino</option>
                                        <option value="Otro"      {{ old('sexo', $user->sexo)=='Otro'?'selected':'' }}>Otro</option>
                                    </select>
                                    @error('sexo')<div class="error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="profile-actions">
                        <a class="btn btn-secondary" href="{{ route('doctor.dashboard') }}">Regresar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const changePhoto = document.getElementById('changePhoto');
            const input = document.getElementById('avatarInput');
            const preview = document.getElementById('avatarPreview');
            if (changePhoto && input && preview) {
                changePhoto.addEventListener('click', () => input.click());
                input.addEventListener('change', (e) => {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    preview.src = URL.createObjectURL(file);
                });
            }
        });
    </script>
@endpush
