{{-- resources/views/admin/doctor-create.blade.php --}}
@extends('layouts.admin')
@section('title','Crear doctor | Admin')

@push('head')
<style>
  .page{max-width:880px;margin:0 auto}
  .card{background:#fff;border:1px solid #e7ebf3;border-radius:16px;box-shadow:0 10px 24px rgba(16,24,40,.06);padding:20px}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .full{grid-column:1/-1}
  label{display:block;margin:2px 0 6px;color:#677483;font-size:.92rem}
  .field{padding-bottom:12px;border-bottom:1px dashed #e2e8f0}
  .field:last-child{border-bottom:0}
  .input,.select{width:100%;height:44px;background:#fbfcfe;border:1.5px solid #c3ccda;border-radius:12px;padding:0 .85rem}
  .input:focus,.select:focus{outline:none;border-color:#7380ec;box-shadow:0 0 0 4px rgba(115,128,236,.18)}
  .input-wrap{position:relative}.input-wrap .input{padding-right:2.6rem}
  .toggle-visibility{position:absolute;right:.45rem;top:50%;transform:translateY(-50%);border:0;background:transparent;cursor:pointer}

  /* Estilos para radios en formato "chip" (SOLO UNA OPCIÓN) */
  .chips{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem}
  .chip{
    position:relative; cursor:pointer; user-select:none;
    display:flex; align-items:center; gap:.55rem;
    border:1.5px solid #c3ccda; border-radius:12px;
    padding:.65rem .8rem; background:#fff; transition:all .15s ease-in-out;
  }
  .chip:hover{border-color:#9aa4b6;background:#f7f9ff}
  .chip > input{position:absolute; inset:0; opacity:0; cursor:pointer}
  .chip:has(> input:checked){
    background:#eef2ff; border-color:#7380ec; box-shadow:0 0 0 3px rgba(115,128,236,.14) inset;
  }
  .chip:has(> input:checked)::before{
    content:"radio_button_checked"; font-family:"Material Symbols Outlined";
    font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:50%; color:#7380ec; font-size:18px;
  }
  .chip::before{
    content:"radio_button_unchecked"; font-family:"Material Symbols Outlined";
    font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:50%; color:#9aa4b6; font-size:18px;
  }

  .actions{margin-top:16px;display:flex;justify-content:flex-end}
  .btn{border:0;padding:.85rem 1.1rem;border-radius:12px;font-weight:800;cursor:pointer;background:#7380ec;color:#fff}
  .error-text{margin-top:6px;color:#b91c1c}
  .hint{color:#64748b;font-size:.9rem;margin-top:6px}
</style>
@endpush

@section('main')
  <div class="page">
    <h2 style="text-align:center;color:#4f46e5;font-weight:800;margin:0 0 14px">Registrar nuevo doctor</h2>

    @if(session('success'))
      <div class="card" style="border:1px solid #c7f0d2;background:#f0fdf4;color:#14532d;margin-bottom:12px">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="card" style="border:1px solid #fecaca;background:#fef2f2;color:#7f1d1d;margin-bottom:12px">
        <ul style="margin:0 0 0 18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form id="form-crear-doctor" class="card" action="{{ route('admin.doctores.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      <div class="grid">
        <div class="field">
          <label>Nombre</label>
          <input class="input" type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field">
          <label>Correo</label>
          <input class="input" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="field">
          <label>Contraseña</label>
          <div class="input-wrap">
            <input class="input" type="password" id="password" name="password" required>
            <button type="button" class="toggle-visibility" data-target="password" title="Mostrar/ocultar">
              <span class="material-symbols-outlined">visibility</span>
            </button>
          </div>
        </div>
        <div class="field">
          <label>Confirmar contraseña</label>
          <div class="input-wrap">
            <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
            <button type="button" class="toggle-visibility" data-target="password_confirmation" title="Mostrar/ocultar">
              <span class="material-symbols-outlined">visibility</span>
            </button>
          </div>
        </div>

        <div class="field">
          <label>Teléfono (10 dígitos)</label>
          <input class="input" type="text" name="telefono" value="{{ old('telefono') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="0991234567">
        </div>
        <div class="field">
          <label>Cédula (10 dígitos)</label>
          <input class="input" type="text" name="dni" value="{{ old('dni') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="1750XXXXXX">
        </div>

        <div class="full field">
          <label>Dirección</label>
          <input class="input" type="text" name="direccion" value="{{ old('direccion') }}">
        </div>

        <div class="field">
          <label>Fecha de nacimiento</label>
          <input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
        </div>
        <div class="field">
          <label>Sexo</label>
          <select class="select" name="sexo">
            <option value="">Seleccionar</option>
            <option value="Masculino" @selected(old('sexo')==='Masculino')>Masculino</option>
            <option value="Femenino" @selected(old('sexo')==='Femenino')>Femenino</option>
            <option value="Otro" @selected(old('sexo')==='Otro')>Otro</option>
          </select>
        </div>

        <div class="full field">
          <label>Foto (opcional)</label>
          <input class="input" type="file" name="avatar" accept="image/*">
        </div>

        {{-- ESPECIALIDAD ÚNICA (OBLIGATORIA) --}}
        <div class="full field">
          <label>Especialidad del doctor <span style="color:#b91c1c">*</span></label>
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

      <div class="actions">
        <button type="submit" class="btn">Guardar doctor</button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
  // Mostrar/ocultar contraseña
  document.querySelectorAll('.toggle-visibility').forEach((btn)=>{
    const input=document.getElementById(btn.dataset.target);
    const icon=btn.querySelector('.material-symbols-outlined');
    btn.addEventListener('click',()=>{
      const isText = input.type==='text';
      input.type = isText ? 'password' : 'text';
      icon.textContent = isText ? 'visibility' : 'visibility_off';
    });
  });

  // Fallback visual si el navegador no soporta :has()
  (function(){
    const supportsHas = CSS.supports && CSS.supports('selector(:has(*))');
    if (supportsHas) return;
    // Para radios, aplicar clase activa al contenedor seleccionado
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
  /* Fallback visual (cuando no hay :has) */
  .chip.chip--active{
    background:#eef2ff; border-color:#7380ec; box-shadow:0 0 0 3px rgba(115,128,236,.14) inset;
  }
  .chip.chip--active::before{
    content:"radio_button_checked"; font-family:"Material Symbols Outlined";
    font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:50%; color:#7380ec; font-size:18px;
  }
</style>
@endpush
