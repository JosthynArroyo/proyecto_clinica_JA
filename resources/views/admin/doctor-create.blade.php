@extends('layouts.admin')
@section('title','Crear doctor | Admin')

@push('head')
<style>
  :root{
    --doctor-primary:#0ea5e9;
    --doctor-border:#bae6fd;
    --doctor-muted:#64748b;
    --doctor-dark:#0f172a;
  }
  body{
    background:radial-gradient(1400px 480px at 75% -18%,#e0f2fe 0%,#ffffff 60%);
  }
  .page{width:min(1040px,100%);margin:2.6rem auto;padding:0 1.4rem 3rem;}
  .hero{display:flex;flex-wrap:wrap;gap:1.4rem;justify-content:space-between;align-items:flex-end;margin-bottom:1.8rem;}
  .hero h2{margin:0;font-size:2.1rem;font-weight:800;color:var(--doctor-dark);}
  .hero p{margin:.4rem 0 0;max-width:36ch;color:var(--doctor-muted);font-weight:500;}
  .hero-stat{display:flex;align-items:center;gap:.6rem;background:rgba(14,165,233,.12);border:1px solid var(--doctor-border);padding:.7rem 1.1rem;border-radius:999px;font-weight:700;color:var(--doctor-dark);}
  .hero-stat .material-symbols-outlined{color:var(--doctor-primary);font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;}
  .alerts{display:grid;gap:1rem;margin-bottom:1.2rem;}
  .alert{border-radius:16px;padding:1rem 1.2rem;font-weight:600;}
  .alert.success{background:#ecfdf5;border:1px solid #bbf7d0;color:#047857;}
  .alert.error{background:#fff0f6;border:1px solid #fbcfe8;color:#9f1239;}
  .card{background:#fff;border-radius:24px;border:1px solid #e0f2fe;box-shadow:0 32px 44px rgba(15,23,42,.08);overflow:hidden;}
  .card-header{padding:1.6rem 2rem;background:linear-gradient(180deg,#f0f9ff 0%,#ffffff 80%);border-bottom:1px solid #e0f2fe;display:flex;flex-wrap:wrap;gap:1.4rem;justify-content:space-between;align-items:center;}
  .card-header h3{margin:0;font-size:1.4rem;font-weight:800;color:var(--doctor-dark);}
  .card-header span{color:var(--doctor-muted);font-size:.95rem;}
  .card-body{padding:2rem;display:grid;gap:2rem;}
  .section{border:1px dashed #cbd5f5;border-radius:18px;padding:1.6rem;background:#f7fbff;}
  .section-title{margin:0 0 1rem;font-size:1.05rem;font-weight:700;color:var(--doctor-dark);display:flex;align-items:center;gap:.6rem;}
  .section-title .material-symbols-outlined{color:var(--doctor-primary);font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;}
  .grid{display:grid;gap:1.1rem 1.2rem;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));}
  label{display:block;margin-bottom:.45rem;font-weight:700;color:var(--doctor-dark);}
  .input,.select{width:100%;height:48px;border-radius:14px;border:1.6px solid #cfe5fb;background:#f4f9ff;padding:0 1rem;font-weight:600;color:var(--doctor-dark);transition:border .15s,box-shadow .15s,background .15s;}
  .input:focus,.select:focus{outline:none;border-color:var(--doctor-primary);box-shadow:0 0 0 6px rgba(14,165,233,.22);background:#fff;}
  .input-wrap{position:relative;}
  .input-wrap .input{padding-right:2.6rem;}
  .toggle-visibility{position:absolute;right:.55rem;top:50%;transform:translateY(-50%);border:0;background:transparent;cursor:pointer;color:var(--doctor-primary);}
  .chips{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.75rem;}
  .chip{position:relative;display:flex;align-items:center;gap:.6rem;border:1.5px solid #cfe5fb;border-radius:14px;background:#fff;padding:.75rem 1rem;cursor:pointer;transition:border .15s,box-shadow .15s,background .15s;}
  .chip:hover{border-color:var(--doctor-primary);background:#f0f9ff;}
  .chip > input{position:absolute;inset:0;opacity:0;cursor:pointer;}
  .chip:has(> input:checked){background:#e0f2fe;border-color:var(--doctor-primary);box-shadow:0 0 0 3px rgba(14,165,233,.18) inset;}
  .chip:has(> input:checked)::before{content:"stethoscope";font-family:"Material Symbols Outlined";font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;color:var(--doctor-primary);}
  .chip::before{content:"radio_button_unchecked";font-family:"Material Symbols Outlined";font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;color:#94a3b8;}
  .error-text{margin-top:.45rem;color:#b91c1c;font-weight:600;}
  .hint{color:var(--doctor-muted);font-size:.88rem;margin-top:.35rem;}
  .card-footer{padding:0 2rem 2rem;display:flex;justify-content:flex-end;gap:.75rem;flex-wrap:wrap;}
  .btn{border:none;border-radius:14px;padding:.9rem 1.4rem;font-weight:800;cursor:pointer;transition:transform .05s,filter .2s,box-shadow .2s;}
  .btn:active{transform:translateY(1px) scale(.995);}
  .btn-secondary{background:#f0f9ff;border:1px solid #bae6fd;color:var(--doctor-dark);}
  .btn-primary{background:linear-gradient(135deg,#0ea5e9,#2563eb);color:#fff;box-shadow:0 22px 36px rgba(14,165,233,.28);}
  @media (max-width:760px){
    .card-header{flex-direction:column;align-items:flex-start;}
  }
</style>
@endpush

@section('main')
  <div class="page">
    <div class="hero">
      <div>
        <h2>Registrar nuevo doctor</h2>
        <p>Captura los datos profesionales y de acceso para incorporar al especialista a la clínica.</p>
      </div>
      <span class="hero-stat"><span class="material-symbols-outlined">workspace_premium</span>Perfil profesional</span>
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

    <form id="form-crear-doctor" class="card" action="{{ route('admin.doctores.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      <div class="card-header">
        <div>
          <h3>Información del doctor</h3>
          <span>Verifica que los datos coincidan con la documentación entregada.</span>
        </div>
        <span class="hero-stat" style="background:#e0f2fe;">1 solo formulario</span>
      </div>

      <div class="card-body">
        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">badge</span>Identificación</h4>
          <div class="grid">
            <div>
              <label>Nombre completo</label>
              <input class="input" type="text" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
              <label>Cédula</label>
              <input class="input" type="text" name="dni" value="{{ old('dni') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="1750XXXXXX">
            </div>
            <div>
              <label>Fecha de nacimiento</label>
              <input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
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
              <input class="input" type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div>
              <label>Teléfono</label>
              <input class="input" type="text" name="telefono" value="{{ old('telefono') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="0991234567">
              <div class="hint">Ingresa solo números (10 dígitos).</div>
            </div>
            <div class="grid" style="grid-template-columns:1fr;">
              <label>Dirección</label>
              <input class="input" type="text" name="direccion" value="{{ old('direccion') }}">
            </div>
          </div>
        </section>

        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">lock</span>Credenciales de acceso</h4>
          <div class="grid">
            <div>
              <label>Contraseña</label>
              <div class="input-wrap">
                <input class="input" type="password" id="password" name="password" required>
                <button type="button" class="toggle-visibility" data-target="password" title="Mostrar/ocultar">
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>
            <div>
              <label>Confirmar contraseña</label>
              <div class="input-wrap">
                <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
                <button type="button" class="toggle-visibility" data-target="password_confirmation" title="Mostrar/ocultar">
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <section class="section">
          <h4 class="section-title"><span class="material-symbols-outlined">medication</span>Detalles profesionales</h4>
          <div class="grid">
            <div>
              <label>Foto (opcional)</label>
              <input class="input" type="file" name="avatar" accept="image/*">
            </div>
            <div>
              <label>Especialidad</label>
              <div class="chips">
                @foreach(($especialidades ?? []) as $esp)
                  <label class="chip">
                    <input
                      type="radio"
                      name="especialidad_id"
                      value="{{ $esp->id }}"
                      required
                      @checked( (string)old('especialidad_id') === (string)$esp->id )
                    >
                    <span>{{ $esp->nombre }}</span>
                  </label>
                @endforeach
              </div>
              @error('especialidad_id')<div class="error-text">{{ $message }}</div>@enderror
              <div class="hint">Selecciona exactamente una especialidad.</div>
            </div>
          </div>
        </section>
      </div>

      <div class="card-footer">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar doctor</button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.toggle-visibility').forEach((btn)=>{
    const input=document.getElementById(btn.dataset.target);
    const icon=btn.querySelector('.material-symbols-outlined');
    btn.addEventListener('click',()=>{
      const isText = input.type==='text';
      input.type = isText ? 'password' : 'text';
      icon.textContent = isText ? 'visibility' : 'visibility_off';
    });
  });

  (function(){
    const supportsHas = CSS.supports && CSS.supports('selector(:has(*))');
    if (supportsHas) return;
    const radios = Array.from(document.querySelectorAll('input[name="especialidad_id"]'));
    const paint = ()=>{
      radios.forEach(r=>{
        const chip = r.closest('.chip');
        chip.classList.toggle('chip--active', r.checked);
      });
    };
    radios.forEach(r=>r.addEventListener('change', paint));
    paint();
  })();
</script>

<style>
  .chip.chip--active{
    background:#e0f2fe; border-color:#0ea5e9; box-shadow:0 0 0 3px rgba(14,165,233,.18) inset;
  }
  .chip.chip--active::before{
    content:"stethoscope"; font-family:"Material Symbols Outlined";
    font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:50%; color:#0ea5e9; font-size:18px;
  }
</style>
@endpush
