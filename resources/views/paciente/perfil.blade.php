@extends('layouts.paciente')
@section('title','Perfil del Paciente')

@push('head')
<style>
  body{
    background:radial-gradient(1600px 520px at 35% -18%,#dcfce7 0%,#ffffff 55%);
  }

  .perfil-page{width:min(1040px,100%);margin:90px auto 60px;padding:0 24px 60px;display:grid;gap:24px;}
  .perfil-hero{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1.4rem;padding:28px 32px;border-radius:28px;background:linear-gradient(120deg,rgba(16,185,129,.15),rgba(34,197,94,.18));border:1px solid rgba(16,185,129,.28);box-shadow:0 26px 42px rgba(15,118,110,.12);} 
  .perfil-hero h2{margin:0;font-size:2.05rem;font-weight:800;color:#064e3b;} 
  .perfil-hero p{margin:.6rem 0 0;color:#0f766e;font-weight:500;max-width:48ch;} 
  .perfil-hero .badge{display:inline-flex;align-items:center;gap:.55rem;padding:.6rem 1.1rem;border-radius:999px;background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.32);font-weight:700;color:#064e3b;} 
  .perfil-hero .badge .material-symbols-outlined{font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;color:#10b981;} 

  .perfil-layout{display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start;}
  .perfil-aside{background:#fff;border-radius:24px;border:1px solid rgba(16,185,129,.25);box-shadow:0 22px 38px rgba(15,118,110,.12);padding:28px;display:grid;gap:20px;}
  .avatar{position:relative;width:130px;height:130px;border-radius:999px;margin:0 auto;overflow:hidden;border:4px solid #bbf7d0;box-shadow:0 14px 22px rgba(15,118,110,.18);}
  .avatar img{width:100%;height:100%;object-fit:cover;display:block;}
  .overlay{position:absolute;left:0;right:0;bottom:0;height:48px;background:linear-gradient(180deg,transparent,rgba(15,118,110,.8));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.9rem;cursor:pointer;opacity:0;transition:opacity .2s;}
  .avatar:hover .overlay{opacity:1;}
  .perfil-aside h3{text-align:center;margin:0;font-size:1.35rem;font-weight:800;color:#064e3b;}
  .perfil-aside span{text-align:center;color:#0f766e;font-weight:600;}
  .perfil-aside .summary{display:grid;gap:.45rem;}
  .summary-item{display:flex;align-items:center;gap:.55rem;padding:.55rem .75rem;border-radius:14px;background:rgba(16,185,129,.12);color:#065f46;font-weight:600;font-size:.9rem;}
  .summary-item .material-symbols-outlined{color:#0ea5a5;font-variation-settings:'FILL' 1,'wght' 500,'GRAD' 0,'opsz' 24;}

  .perfil-card{background:#fff;border-radius:24px;border:1px solid rgba(16,185,129,.25);box-shadow:0 26px 42px rgba(15,118,110,.12);overflow:hidden;}
  .perfil-card__header{padding:1.6rem 2.1rem;background:linear-gradient(180deg,#ecfdf5 0%,#ffffff 80%);border-bottom:1px solid rgba(16,185,129,.25);} 
  .perfil-card__header h4{margin:0;font-size:1.4rem;font-weight:800;color:#064e3b;} 
  .perfil-card__header span{color:#0f766e;font-size:.95rem;}

  .perfil-alerts{display:grid;gap:1rem;margin:0 2.1rem;}
  .alert{padding:1rem 1.2rem;border-radius:18px;font-weight:600;border:1px solid transparent;}
  .alert.success{background:#ecfdf5;border-color:#bbf7d0;color:#047857;}
  .alert.error{background:#fef2f2;border-color:#fecaca;color:#b91c1c;}

  .perfil-body{padding:2.1rem;display:grid;gap:2rem;}
  .section{border:1px dashed rgba(16,185,129,.28);border-radius:20px;padding:1.6rem;background:#f7fdf9;}
  .section-title{margin:0 0 1.05rem;font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:.55rem;color:#065f46;}
  .section-title .material-symbols-outlined{font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;color:#10b981;}
  .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:1.2rem 1.4rem;}
  label{display:block;font-weight:700;margin-bottom:.45rem;color:#064e3b;}
  .input,.select{width:100%;height:48px;border-radius:14px;border:1.5px solid rgba(16,185,129,.28);background:#fff;padding:0 1.05rem;font-weight:600;color:#064e3b;transition:border .15s,box-shadow .15s;}
  .input:focus,.select:focus{outline:none;border-color:#10b981;box-shadow:0 0 0 6px rgba(16,185,129,.18);}
  .field-help{font-size:.82rem;color:#0f766e;margin-top:.35rem;}
  .error{font-size:.85rem;color:#dc2626;margin-top:.35rem;font-weight:600;}

  .perfil-actions{display:flex;justify-content:flex-end;gap:.7rem;padding:0 2.1rem 2.1rem;flex-wrap:wrap;}
  .btn{border:none;border-radius:14px;padding:.8rem 1.3rem;font-weight:800;cursor:pointer;font-size:.95rem;transition:transform .05s,filter .2s,box-shadow .2s;}
  .btn:active{transform:translateY(1px) scale(.995);}
  .btn-secondary{background:#e0f2f1;border:1px solid rgba(13,148,136,.32);color:#0f766e;}
  .btn-primary{background:linear-gradient(135deg,#10b981,#0ea5a5);color:#fff;box-shadow:0 18px 28px rgba(16,185,129,.25);}

  @media (max-width:960px){
    .perfil-layout{grid-template-columns:1fr;}
    .perfil-aside{order:2;}
  }
</style>
@endpush

@section('main')
<div class="perfil-page">
  <section class="perfil-hero">
    <div>
      <h2>Perfil del paciente</h2>
      <p>Mantén tu información actualizada para recibir recordatorios y comunicaciones de manera oportuna.</p>
    </div>
    <span class="badge"><span class="material-symbols-outlined">favorite</span>Atención personalizada</span>
  </section>

  <div class="perfil-layout">
    <aside class="perfil-aside">
      <div class="avatar">
        <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('img/paciente1.jpg') }}" alt="Avatar">
        <div id="changePhoto" class="overlay">Cambiar foto</div>
      </div>
      <div>
        <h3>{{ $user->name }}</h3>
        <span>{{ $user->email }}</span>
      </div>
      <div class="summary">
        @if($user->telefono)
          <div class="summary-item"><span class="material-symbols-outlined">call</span>{{ $user->telefono }}</div>
        @endif
        @if($user->direccion)
          <div class="summary-item"><span class="material-symbols-outlined">location_on</span>{{ $user->direccion }}</div>
        @endif
      </div>
    </aside>

    <section class="perfil-card">
      <div class="perfil-card__header">
        <h4>Datos personales</h4>
        <span>Actualiza tus datos de contacto y verificación.</span>
      </div>

      @if(session('success'))
        <div class="perfil-alerts"><div class="alert success">{{ session('success') }}</div></div>
      @endif
      @if ($errors->any())
        <div class="perfil-alerts">
          <div class="alert error">
            <ul style="margin:0 0 0 18px;padding:0;">
              @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('paciente.perfil.update') }}" enctype="multipart/form-data">
        @csrf
        <input id="avatarInput" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" hidden>

        <div class="perfil-body">
          <section class="section">
            <h5 class="section-title"><span class="material-symbols-outlined">badge</span>Identificación</h5>
            <div class="grid">
              <div>
                <label>Nombre</label>
                <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
              </div>
              <div>
                <label>Correo</label>
                <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror
              </div>
              <div>
                <label>Teléfono</label>
                <input class="input" type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="0991234567" title="Debe contener exactamente 10 dígitos">
                <div class="field-help">Formato: 10 dígitos.</div>
                @error('telefono')<div class="error">{{ $message }}</div>@enderror
              </div>
              <div>
                <label>Número de Cédula</label>
                <input class="input" type="text" name="dni" value="{{ old('dni', $user->dni) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="1723456789" title="Debe contener exactamente 10 dígitos">
                <div class="field-help">Exactamente 10 dígitos.</div>
                @error('dni')<div class="error">{{ $message }}</div>@enderror
              </div>
            </div>
          </section>

          <section class="section">
            <h5 class="section-title"><span class="material-symbols-outlined">home_pin</span>Información adicional</h5>
            <div class="grid">
              <div>
                <label>Dirección</label>
                <input class="input" type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}">
                @error('direccion')<div class="error">{{ $message }}</div>@enderror
              </div>
              <div>
                <label>Fecha de nacimiento</label>
                <input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->toDateString()) }}">
                @error('fecha_nacimiento')<div class="error">{{ $message }}</div>@enderror
              </div>
              <div>
                <label>Sexo</label>
                <select class="select" name="sexo">
                  <option value="">Seleccionar</option>
                  <option value="Masculino" {{ old('sexo', $user->sexo)=='Masculino'?'selected':'' }}>Masculino</option>
                  <option value="Femenino" {{ old('sexo', $user->sexo)=='Femenino'?'selected':'' }}>Femenino</option>
                  <option value="Otro" {{ old('sexo', $user->sexo)=='Otro'?'selected':'' }}>Otro</option>
                </select>
                @error('sexo')<div class="error">{{ $message }}</div>@enderror
              </div>
            </div>
          </section>
        </div>

        <div class="perfil-actions">
          <a href="{{ route('paciente.dashboard') }}" class="btn btn-secondary">Ir al panel</a>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </section>
  </div>
</div>
@endsection

@push('scripts')
<script>
const changePhoto=document.getElementById('changePhoto');
const input=document.getElementById('avatarInput');
const preview=document.getElementById('avatarPreview');
if(changePhoto&&input&&preview){
  const box=changePhoto.parentElement;
  box.addEventListener('mouseenter',()=>changePhoto.style.opacity=1);
  box.addEventListener('mouseleave',()=>changePhoto.style.opacity=0);
  changePhoto.addEventListener('click',()=>input.click());
  input.addEventListener('change',e=>{
    const f=e.target.files?.[0];
    if(!f)return;
    const url=URL.createObjectURL(f);
    preview.src=url
  })
}
</script>
@endpush
