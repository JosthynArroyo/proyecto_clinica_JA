@extends('layouts.admin')
@section('title','Crear usuario')
@push('head')
<style>
  .wrap{max-width:880px;margin:96px auto 40px;padding:0 24px}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 18px 34px rgba(15,23,42,.08);padding:22px}
  .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
  .full{grid-column:1/-1}
  label{font-weight:700;color:#0f172a}
  .input,.select{width:100%;height:44px;border:1.5px solid #e5e7eb;border-radius:12px;padding:0 12px;font-weight:600}
  .btn{display:inline-flex;gap:.5rem;align-items:center;border:0;border-radius:12px;padding:.8rem 1.2rem;font-weight:700;cursor:pointer;background:#1d4ed8;color:#fff}
  .alert{padding:12px 14px;border-radius:12px;margin:12px 0;font-weight:700}
  .alert-error{background:#fff0f2;color:#9f1239;border:1px solid #fecdd3}
  .alert-success{background:#ecfdf5;color:#047857;border:1px solid #bbf7d0}
</style>
@endpush

@section('main')
@php $preset = request('role'); @endphp
<div class="wrap">
  <h2 style="margin:0 0 16px 0;font-weight:800">Nuevo usuario</h2>

  @if ($errors->any())
    <div class="alert alert-error">
      @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form class="card" method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Preselección de rol opcional vía ?role=doctor|paciente|administrador --}}
    @if($preset)
      <input type="hidden" name="role_id" value="{{ optional($roles->firstWhere('name',$preset))->id }}">
    @endif

    @include('admin.users.form', ['user'=>null, 'roles'=>$roles, 'especialidades'=>$especialidades])

    <div style="display:flex;gap:12px;margin-top:12px">
      <button class="btn" type="submit"><span class="material-symbols-outlined">save</span> Crear</button>
      <a class="btn" style="background:#64748b" href="{{ route('admin.usuarios.index') }}">Cancelar</a>
    </div>
  </form>
</div>
@endsection
