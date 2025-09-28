@extends('layouts.admin')
@section('title','Perfil del Administrador')

@push('head')
<style>
  .page{max-width:980px;margin:0 auto}
  .card{background:#fff;border-radius:18px;box-shadow:0 28px 40px rgba(31,35,48,.08), 0 8px 18px rgba(31,35,48,.06);border:1px solid #eef1ff;overflow:hidden}
  .card-header{display:flex;align-items:center;gap:20px;padding:26px;background:linear-gradient(180deg,#fafbff 0%, #ffffff 70%);border-bottom:1px solid #eef1ff}
  .avatar{position:relative;width:110px;height:110px;border-radius:999px;overflow:hidden;border:3px solid #eef2ff;box-shadow:0 8px 16px rgba(17,24,39,.08)}
  .avatar img{width:100%;height:100%;object-fit:cover}
  .overlay{position:absolute;left:0;right:0;bottom:0;height:42px;background:linear-gradient(180deg,transparent,rgba(0,0,0,.65));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.86rem;opacity:0;transition:opacity .2s;cursor:pointer}
  .avatar:hover .overlay{opacity:1}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:26px}
  label{display:block;font-size:.9rem;font-weight:700;margin:0 0 .5rem;color:#2b2f43}
  .input,.select{width:100%;padding:12px 14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;font-size:.96rem;font-weight:600;color:#1f2937}
  .input:focus,.select:focus{border-color:#7380ec;box-shadow:0 0 0 6px rgba(115,128,236,.18);background:#fff;outline:none}
  .field{padding-bottom:12px;border-bottom:1px dashed #e2e8f0}
  .field:last-child{border-bottom:0}
  .actions{display:flex;gap:12px;justify-content:flex-end;margin:0 26px 26px}
  .btn{border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;font-size:.95rem;color:#fff;background:linear-gradient(135deg,#7380ec,#8ea1ff)}
  .alert{padding:12px 14px;border-radius:14px;margin:16px 26px 0;font-weight:700}
  .alert.success{background:#eafcf4;color:#0f6a4f;border:1px solid #b5f1de}
  .alert.danger{background:#fff0f2;color:#a21736;border:1px solid #ffd6df}
  @media (max-width:820px){.grid{grid-template-columns:1fr}.page{max-width:95%}}
</style>
@endpush

@section('main')
  <div class="page">
    <div class="card">
      <div class="card-header">
        <div class="avatar">
          <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('img/doctor1.jpg') }}" alt="Avatar">
          <div class="overlay" id="changePhoto">Cambiar foto</div>
        </div>
        <div class="title">
          <h1 style="margin:0;font-size:1.5rem;font-weight:800">Perfil del Administrador</h1>
          <p style="margin:.35rem 0 0;color:#6b7280;font-size:.96rem">Información de tu cuenta administrativa.</p>
        </div>
      </div>

      @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
      @if ($errors->any())
        <div class="alert danger">
          <ul style="margin:0 0 0 18px;padding:0;">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.perfil.update') }}" enctype="multipart/form-data">
        @csrf
        <input id="avatarInput" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" hidden>

        <div class="grid">
          <div class="field"><label>Nombre</label><input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
          <div class="field"><label>Correo</label><input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
          <div class="field"><label>Teléfono</label><input class="input" type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="0998740927"><div style="font-size:.8rem;color:#6b7280;margin-top:.35rem">Formato: 10 dígitos.</div></div>
          <div class="field"><label>Número de Cédula</label><input class="input" type="text" name="dni" value="{{ old('dni', $user->dni) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="1723456789"><div style="font-size:.8rem;color:#6b7280;margin-top:.35rem">Exactamente 10 dígitos.</div></div>
          <div class="field"><label>Dirección</label><input class="input" type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}"></div>
          <div class="field"><label>Fecha de nacimiento</label><input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->toDateString()) }}"></div>
          <div class="field"><label>Sexo</label>
            <select class="select" name="sexo">
              <option value="">Seleccionar</option>
              <option value="Masculino" {{ old('sexo', $user->sexo)=='Masculino'?'selected':'' }}>Masculino</option>
              <option value="Femenino"  {{ old('sexo', $user->sexo)=='Femenino'?'selected':'' }}>Femenino</option>
              <option value="Otro"      {{ old('sexo', $user->sexo)=='Otro'?'selected':'' }}>Otro</option>
            </select>
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="btn">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  const changePhoto=document.getElementById('changePhoto');
  const input=document.getElementById('avatarInput');
  const preview=document.getElementById('avatarPreview');
  changePhoto.addEventListener('click',()=>input.click());
  input.addEventListener('change',(e)=>{const f=e.target.files?.[0];if(!f)return;preview.src=URL.createObjectURL(f);});
</script>
@endpush
