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
            background: radial-gradient(1200px 500px at 20% -10%, #f1f3ff 0%, #ffffff 60%);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
            color: #1f2937;
        }
        .profile-wrapper {
            max-width: 980px;
            margin: 2.4rem auto 0;
            padding: 0 20px;
        }
        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 28px 40px rgba(31, 35, 48, .08), 0 8px 18px rgba(31, 35, 48, .06);
            border: 1px solid #eef1ff;
            overflow: hidden;
        }
        .profile-card__header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 26px;
            background: linear-gradient(180deg, #fafbff 0%, #ffffff 70%);
            border-bottom: 1px solid #eef1ff;
        }
        .profile-avatar {
            position: relative;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            overflow: hidden;
            flex: 0 0 auto;
            border: 3px solid #eef2ff;
            box-shadow: 0 8px 16px rgba(17, 24, 39, .08);
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .profile-avatar__overlay {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 42px;
            background: linear-gradient(180deg, transparent, rgba(0, 0, 0, .65));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .86rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity .2s;
        }
        .profile-avatar:hover .profile-avatar__overlay {
            opacity: 1;
        }
        .profile-title h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
        }
        .profile-title p {
            margin: .35rem 0 0;
            color: #6b7280;
            font-size: .96rem;
        }
        .profile-card__body {
            padding: 26px;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        @media (max-width: 820px) {
            .profile-card__header {
                flex-direction: column;
                align-items: flex-start;
            }
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
        label {
            display: block;
            font-size: .9rem;
            font-weight: 700;
            margin: 0 0 .5rem;
            color: #2b2f43;
        }
        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #f9fafb;
            outline: none;
            font-size: .96rem;
            font-weight: 600;
            color: #1f2937;
            transition: border .15s, box-shadow .15s, background .15s;
        }
        input:focus,
        select:focus {
            border-color: #6b74ff;
            box-shadow: 0 0 0 6px rgba(107, 116, 255, .18);
            background: #fff;
        }
        .help {
            font-size: .8rem;
            color: #6b7280;
            margin-top: .35rem;
        }
        .error {
            font-size: .85rem;
            color: #ef4444;
            margin-top: .35rem;
            font-weight: 600;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 16px;
            font-weight: 700;
        }
        .alert.success {
            background: #eafcf4;
            color: #0f6a4f;
            border: 1px solid #b5f1de;
        }
        .alert.danger {
            background: #fff0f2;
            color: #a21736;
            border: 1px solid #ffd6df;
        }
        .profile-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .btn {
            border: none;
            border-radius: 14px;
            padding: 12px 18px;
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
            background: linear-gradient(135deg, #6b74ff, #8ea1ff);
            box-shadow: 0 14px 26px rgba(107, 116, 255, .28);
        }
        .btn-primary:hover {
            filter: brightness(1.03);
        }
        .btn-secondary {
            background: #eef2ff;
            color: #2a2f45;
            border: 1px solid #e1e7ff;
        }
        .btn-secondary:hover {
            filter: brightness(1.02);
        }
        .hidden {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="profile-wrapper">
        <div class="profile-card">
            <div class="profile-card__header">
                <div class="profile-avatar">
                    <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('img/doctor1.jpg') }}" alt="Avatar">
                    <div class="profile-avatar__overlay" id="changePhoto">Cambiar foto</div>
                </div>
                <div class="profile-title">
                    <h1>Perfil del Doctor</h1>
                    <p>Gestiona tu información.</p>
                </div>
            </div>
            <div class="profile-card__body">
                @if(session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert danger">
                        <ul style="margin: 0 0 0 18px; padding: 0;">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('doctor.perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input id="avatarInput" class="hidden" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp">
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
                    <div class="profile-actions">
                        <a class="btn btn-secondary" href="{{ route('doctor.dashboard') }}">Regresar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
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