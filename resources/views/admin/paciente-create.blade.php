@extends('layouts.admin')
@section('title','Crear paciente | Admin')

@push('head')
<style>
  :root{
    --paciente-primary:#6366f1;
    --paciente-primary-soft:rgba(99,102,241,.12);
    --paciente-border:#dbe3ff;
    --paciente-dark:#111827;
    --paciente-muted:#6b7280;
    --paciente-card:#ffffff;
  }

  body{
    background:radial-gradient(1400px 480px at 16% -16%,#eef2ff 0%,#ffffff 60%);
  }

  .page{
    width:min(980px,100%);
    margin:2.6rem auto 2rem;
    padding:0 1.4rem 3rem;
  }

  .header{
    display:flex;
    flex-wrap:wrap;
    justify-content:space-between;
    align-items:flex-start;
    gap:1.5rem;
    margin-bottom:1.5rem;
  }

  .header h2{
    margin:0;
    font-size:2rem;
    font-weight:800;
    color:var(--paciente-dark);
  }

  .header p{
    margin:.4rem 0 0;
    max-width:32ch;
    color:var(--paciente-muted);
    font-weight:500;
  }

  .badge{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    background:var(--paciente-primary-soft);
    border:1px solid var(--paciente-border);
    color:var(--paciente-primary);
    padding:.55rem 1rem;
    border-radius:999px;
    font-weight:700;
  }

  .card{
    background:var(--paciente-card);
    border-radius:22px;
    border:1px solid #e5e9ff;
    box-shadow:0 32px 44px rgba(17,24,39,.08);
    overflow:hidden;
  }

  .card-header{
    padding:1.6rem 2rem;
    background:linear-gradient(180deg,#f8faff 0%,#ffffff 75%);
    border-bottom:1px solid #e9edff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:1.4rem;
    flex-wrap:wrap;
  }

  .card-header h3{
    margin:0;
    font-size:1.35rem;
    font-weight:800;
    color:var(--paciente-dark);
  }

  .card-header span{
    color:var(--paciente-muted);
    font-size:.95rem;
  }

  .card-body{
    padding:2rem;
    display:grid;
    gap:2rem;
  }

  .section{
    border:1px dashed #e4e8ff;
    border-radius:18px;
    padding:1.6rem;
    background:#f9faff;
  }

  .section-title{
    font-size:1.05rem;
    font-weight:700;
    color:var(--paciente-dark);
    margin:0 0 1rem;
    display:flex;
    align-items:center;
    gap:.6rem;
  }

  .section-title .material-symbols-outlined{
    font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;
    color:var(--paciente-primary);
  }

  .grid{
    display:grid;
    gap:1.1rem 1.2rem;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  }

  label{
    display:block;
    margin-bottom:.45rem;
    font-weight:700;
    color:var(--paciente-dark);
  }

  .input,.select{
    width:100%;
    height:48px;
    border-radius:14px;
    border:1.6px solid #dbe2ff;
    background:#f5f7ff;
    padding:0 1rem;
    font-weight:600;
    color:var(--paciente-dark);
    transition:border .15s,box-shadow .15s,background .15s;
  }

  .input:focus,.select:focus{
    outline:none;
    border-color:var(--paciente-primary);
    box-shadow:0 0 0 6px rgba(99,102,241,.18);
    background:#fff;
  }

  .field-help{
    margin-top:.4rem;
    font-size:.82rem;
    color:var(--paciente-muted);
  }

  .alerts{
    display:grid;
    gap:1rem;
    margin-bottom:1.2rem;
  }

  .alert{
    border-radius:16px;
    padding:1rem 1.2rem;
    font-weight:600;
  }

  .alert.success{
    background:#ecfdf5;
    border:1px solid #bbf7d0;
    color:#047857;
  }

  .alert.error{
    background:#fff1f2;
    border:1px solid #fecdd3;
    color:#9f1239;
  }

  .card-footer{
    display:flex;
    justify-content:flex-end;
    gap:.75rem;
    padding:0 2rem 2rem;
  }

  .btn{
    border:none;
    border-radius:14px;
    padding:0.9rem 1.4rem;
    font-weight:800;
    cursor:pointer;
    transition:transform .05s,filter .2s,box-shadow .2s;
  }

  .btn:active{transform:translateY(1px) scale(.995);}

  .btn-secondary{
    background:#eef2ff;
    border:1px solid var(--paciente-border);
    color:var(--paciente-dark);
  }

  .btn-primary{
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    box-shadow:0 20px 32px rgba(99,102,241,.28);
  }

  @media (max-width:720px){
    .card-header{flex-direction:column;align-items:flex-start;}
  }
</style>
@endpush

@section('main')
  <div class="page">
    <div class="header">
      <div>
        <h2>Registrar nuevo paciente</h2>
        <p>Completa los datos personales, de contacto y acceso para generar la cuenta del paciente.</p>
      </div>
      <span class="badge"><span class="material-symbols-outlined">verified_user</span>Registro seguro</span>
    </div>

    <div class="alerts">
      @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert error">
          <ul style="margin:0 0 0 18px;padding:0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif
    </div>

    <form class="card" method="POST" action="{{ route('admin.pacientes.store') }}" novalidate>
      @csrf
      <div class="card-header">
        <div>
          <h3>Datos del paciente</h3>
          <span>Ingresa la información tal como aparece en la documentación oficial.</span>
        </div>
        <span class="badge" style="background:#f1f5ff">Paso único</span>
      </div>

      <div class="card-body">
        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">badge</span>Identificación</h4>
          <div class="grid">
            <div>
              <label>Nombre completo</label>
              <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Ana Pérez" required>
            </div>
            <div>
              <label>Cédula</label>
              <input class="input" type="text" name="dni" value="{{ old('dni') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="1750XXXXXX" required>
              <div class="field-help">Exactamente 10 dígitos sin espacios.</div>
            </div>
            <div>
              <label>Fecha de nacimiento</label>
              <input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
            </div>
            <div>
              <label>Sexo</label>
              <select class="select" name="sexo">
                <option value="">Seleccionar</option>
                <option value="Masculino" @selected(old('sexo')==='Masculino')>Masculino</option>
                <option value="Femenino" @selected(old('sexo')==='Femenino')>Femenino</option>
                <option value="Otro" @selected(old('sexo')==='Otro')>Otro</option>
              </select>
            </div>
          </div>
        </section>

        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">contact_phone</span>Contacto</h4>
          <div class="grid">
            <div>
              <label>Correo electrónico</label>
              <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="nombre@correo.com" required>
            </div>
            <div>
              <label>Teléfono</label>
              <input class="input" type="text" name="telefono" value="{{ old('telefono') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="0991234567">
              <div class="field-help">Debe contener 10 dígitos.</div>
            </div>
            <div class="grid" style="grid-template-columns:1fr;">
              <label>Dirección</label>
              <input class="input" type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Calle, número y ciudad">
            </div>
          </div>
        </section>

        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">lock</span>Credenciales de acceso</h4>
          <div class="grid">
            <div>
              <label>Contraseña</label>
              <input class="input" type="password" name="password" placeholder="Ingresa una contraseña" required>
            </div>
            <div>
              <label>Confirmar contraseña</label>
              <input class="input" type="password" name="password_confirmation" placeholder="Repite la contraseña" required>
            </div>
          </div>
        </section>
      </div>

      <div class="card-footer">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Registrar paciente</button>
      </div>
    </form>
  </div>
@endsection
