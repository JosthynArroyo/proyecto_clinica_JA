@extends('layouts.admin')
@section('title','Crear paciente | Admin')

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
  .actions{margin-top:16px;display:flex;gap:.6rem;justify-content:flex-end}
  .btn{border:0;padding:.85rem 1.1rem;border-radius:12px;font-weight:800;cursor:pointer;background:#7380ec;color:#fff}
</style>
@endpush

@section('main')
  <div class="page">
    <h2 style="text-align:center;color:#4f46e5;font-weight:800;margin:0 0 14px">Registrar nuevo paciente</h2>

    @if(session('success'))
      <div class="card" style="border:1px solid #a7f3d0;background:#ecfdf5;color:#065f46;margin-bottom:12px">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="card" style="border:1px solid #fecdd3;background:#fff1f2;color:#9f1239;margin-bottom:12px"><ul style="margin:0 0 0 16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form class="card" method="POST" action="{{ route('admin.pacientes.store') }}" novalidate>
      @csrf
      <div class="grid">
        <div class="field"><label>Nombre</label><input class="input" type="text" name="name" value="{{ old('name') }}" required></div>
        <div class="field"><label>Correo</label><input class="input" type="email" name="email" value="{{ old('email') }}" required></div>
        <div class="field"><label>Cédula (10 dígitos)</label><input class="input" type="text" name="dni" value="{{ old('dni') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="1750XXXXXX" required></div>
        <div class="field"><label>Teléfono (10 dígitos)</label><input class="input" type="text" name="telefono" value="{{ old('telefono') }}" minlength="10" maxlength="10" pattern="\d{10}" inputmode="numeric" placeholder="0991234567"></div>
        <div class="full field"><label>Dirección</label><input class="input" type="text" name="direccion" value="{{ old('direccion') }}"></div>
        <div class="field"><label>Fecha de nacimiento</label><input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"></div>
        <div class="field">
          <label>Sexo</label>
          <select class="select" name="sexo">
            <option value="">Seleccionar</option>
            <option value="Masculino" @selected(old('sexo')==='Masculino')>Masculino</option>
            <option value="Femenino" @selected(old('sexo')==='Femenino')>Femenino</option>
            <option value="Otro" @selected(old('sexo')==='Otro')>Otro</option>
          </select>
        </div>
        <div class="field">
          <label>Contraseña</label>
          <input class="input" type="password" id="password" name="password" required>
        </div>
        <div class="field">
          <label>Confirmar contraseña</label>
          <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="btn">Registrar Paciente</button>
      </div>
    </form>
  </div>
@endsection
