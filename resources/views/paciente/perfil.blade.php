@extends('layouts.paciente')
@section('title','Perfil del Paciente')
@section('content')
<div style="grid-column: 2 / span 1;max-width:980px">
  <div style="background:var(--clr-white);border-radius:var(--card-border-radius);box-shadow:var(--box-shadow);border:1px solid var(--clr-info-light);overflow:hidden">
    <div style="display:flex;align-items:center;gap:1rem;padding:1.2rem;border-bottom:1px solid var(--clr-info-light)">
      <div style="position:relative;width:110px;height:110px;border-radius:999px;overflow:hidden;flex:0 0 auto;border:3px solid #eef2ff;box-shadow:0 8px 16px rgba(17,24,39,.08)">
        <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('img/paciente1.jpg') }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
        <div id="changePhoto" style="position:absolute;left:0;right:0;bottom:0;height:42px;background:linear-gradient(180deg,transparent,rgba(0,0,0,.65));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.86rem;opacity:0;transition:.2s;cursor:pointer">Cambiar foto</div>
      </div>
      <div>
        <h2 style="margin:0;font-weight:800;color:var(--clr-dark)">Perfil del Paciente</h2>
        <p style="margin:.2rem 0 0;color:var(--clr-info-dark);font-weight:600">Actualiza tu información personal.</p>
      </div>
    </div>
    <div style="padding:1.2rem">
      @if(session('success'))
        <div style="padding:.75rem 1rem;border-radius:.8rem;margin-bottom:1rem;font-weight:700;background:#eafcf4;color:#0f6a4f;border:1px solid #b5f1de">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div style="padding:.75rem 1rem;border-radius:.8rem;margin-bottom:1rem;font-weight:700;background:#fff0f2;color:#a21736;border:1px solid #ffd6df">
          <ul style="margin:0 0 0 18px;padding:0">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('paciente.perfil.update') }}" enctype="multipart/form-data">
        @csrf
        <input id="avatarInput" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" style="display:none">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            @error('name')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Correo</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            @error('email')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Teléfono</label>
            <input type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="0998740927" title="Debe contener exactamente 10 dígitos" style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            <div style="font-size:.85rem;color:var(--clr-info-dark);margin-top:.35rem">Formato: 10 dígitos.</div>
            @error('telefono')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Número de Cédula</label>
            <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="1723456789" title="Debe contener exactamente 10 dígitos" style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            <div style="font-size:.85rem;color:var(--clr-info-dark);margin-top:.35rem">Exactamente 10 dígitos.</div>
            @error('dni')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}" style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            @error('direccion')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->toDateString()) }}" style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
            @error('fecha_nacimiento')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
          <div>
            <label style="display:block;font-weight:700;margin:0 0 .4rem;color:var(--clr-dark)">Sexo</label>
            <select name="sexo" style="width:100%;padding:.7rem .9rem;border:1px solid var(--clr-info-light);border-radius:.8rem;background:#f9fafb;outline:none;font-weight:600;color:var(--clr-dark)">
              <option value="">Seleccionar</option>
              <option value="Masculino" {{ old('sexo', $user->sexo)=='Masculino'?'selected':'' }}>Masculino</option>
              <option value="Femenino" {{ old('sexo', $user->sexo)=='Femenino'?'selected':'' }}>Femenino</option>
              <option value="Otro" {{ old('sexo', $user->sexo)=='Otro'?'selected':'' }}>Otro</option>
            </select>
            @error('sexo')<div style="font-size:.9rem;color:#ef4444;margin-top:.35rem;font-weight:700">{{ $message }}</div>@enderror
          </div>
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem">
          <a href="{{ route('paciente.dashboard') }}" style="background:var(--clr-light);color:var(--clr-dark);padding:.7rem 1rem;border-radius:.8rem;font-weight:800;display:inline-flex;align-items:center">Ir al Panel</a>
          <button type="submit" style="border:none;border-radius:.8rem;padding:.7rem 1rem;font-weight:800;cursor:pointer;color:#fff;background:var(--clr-primary)">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
const changePhoto=document.getElementById('changePhoto');const input=document.getElementById('avatarInput');const preview=document.getElementById('avatarPreview');if(changePhoto&&input&&preview){const box=changePhoto.parentElement;box.addEventListener('mouseenter',()=>changePhoto.style.opacity=1);box.addEventListener('mouseleave',()=>changePhoto.style.opacity=0);changePhoto.addEventListener('click',()=>input.click());input.addEventListener('change',e=>{const f=e.target.files?.[0];if(!f)return;const url=URL.createObjectURL(f);preview.src=url})}
</script>
@endpush
