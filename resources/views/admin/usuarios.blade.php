@extends('layouts.admin')
@section('title','Usuarios | Admin')
@push('head')
<style>
  :root{
    --usuarios-primary:#1d4ed8;
    --usuarios-primary-soft:rgba(29,78,216,.08);
    --usuarios-highlight:rgba(29,78,216,.12);
    --usuarios-bg:#f5f7fb;
    --usuarios-border:#e2e8f0;
    --usuarios-muted:#64748b;
    --usuarios-dark:#0f172a;
  }
  body{background:var(--usuarios-bg);}
  .users-page{width:min(1240px,100%);margin:96px auto 40px;padding:0 24px 60px;display:grid;gap:28px;}
  .intro{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:1.5rem;padding:28px 32px;border-radius:26px;background:#ffffff;border:1px solid var(--usuarios-border);box-shadow:0 18px 40px rgba(15,23,42,.08);animation:fadeSlideUp .55s ease both;}
  .intro h2{margin:0;font-size:2.1rem;font-weight:800;color:var(--usuarios-dark);}
  .intro p{margin:.6rem 0 0;max-width:48ch;color:var(--usuarios-muted);font-weight:500;}
  .intro-badges{display:flex;flex-wrap:wrap;gap:.75rem;}
  .badge{display:inline-flex;align-items:center;gap:.55rem;padding:.6rem 1.1rem;border-radius:999px;font-weight:700;background:var(--usuarios-primary-soft);color:var(--usuarios-dark);border:1px solid var(--usuarios-border);animation:fadeIn .6s ease both;}
  .intro-badges .badge:nth-child(2){animation-delay:.1s;}
  .badge .material-symbols-outlined{font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;color:var(--usuarios-primary);}
  .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;}
  .stat-card{background:#fff;border-radius:20px;border:1px solid var(--usuarios-border);padding:18px 20px;box-shadow:0 18px 34px rgba(15,23,42,.08);animation:fadeSlideUp .6s ease both;}
  .stat-card:nth-child(2){animation-delay:.1s;}
  .stat-card:nth-child(3){animation-delay:.2s;}
  .stat-card h3{margin:0;font-size:1.9rem;font-weight:800;color:var(--usuarios-dark);}
  .stat-card span{display:flex;align-items:center;gap:.4rem;margin-top:6px;color:var(--usuarios-muted);font-weight:600;font-size:.95rem;}
  .users-card{background:#fff;border-radius:24px;border:1px solid var(--usuarios-border);box-shadow:0 22px 42px rgba(15,23,42,.08);overflow:hidden;animation:fadeSlideUp .7s ease both;}
  .users-card__header{padding:1.6rem 2rem;display:flex;flex-wrap:wrap;gap:1.4rem;justify-content:space-between;align-items:center;background:#ffffff;border-bottom:1px solid var(--usuarios-border);}
  .users-card__header h3{margin:0;font-size:1.35rem;font-weight:800;color:var(--usuarios-dark);}
  .users-card__header span{color:var(--usuarios-muted);font-size:.95rem;}
  .toolbar{display:flex;flex-wrap:wrap;gap:.75rem;align-items:center;}
  .toolbar .input{height:42px;padding:0 1rem;border-radius:14px;border:1.5px solid var(--usuarios-border);background:#ffffff;font-weight:600;color:var(--usuarios-dark);transition:border .2s,box-shadow .2s,background .2s;}
  .toolbar .input:focus{outline:none;border-color:var(--usuarios-primary);box-shadow:0 0 0 5px rgba(29,78,216,.16);background:#fff;}
  .toolbar .btn{height:42px;padding:0 1.1rem;border-radius:14px;border:none;font-weight:700;display:inline-flex;align-items:center;gap:.4rem;cursor:pointer;background:var(--usuarios-primary);color:#fff;box-shadow:0 18px 30px rgba(29,78,216,.22);transition:filter .2s;}
  .toolbar .btn:hover{filter:brightness(.95);}
  table.users{width:100%;border-collapse:separate;border-spacing:0 12px;padding:1.8rem;}
  table.users thead th{padding:0 12px 12px;color:var(--usuarios-muted);font-weight:700;font-size:.88rem;text-transform:uppercase;text-align:left;letter-spacing:.04em;}
  table.users tbody tr{background:#ffffff;border-radius:18px;box-shadow:0 14px 28px rgba(15,23,42,.06);animation:fadeSlideUp .7s ease both;}
  table.users tbody tr:nth-child(n+2){animation-delay:.05s;}
  table.users tbody tr td{padding:18px 16px;background:transparent;border:none;vertical-align:top;}
  table.users tbody tr td:first-child{border-top-left-radius:18px;border-bottom-left-radius:18px;}
  table.users tbody tr td:last-child{border-top-right-radius:18px;border-bottom-right-radius:18px;}
  .user-name{font-weight:700;color:var(--usuarios-dark);font-size:1.02rem;margin-bottom:.35rem;}
  .user-id{font-size:.82rem;color:var(--usuarios-muted);font-weight:600;}
  .chips{display:flex;flex-wrap:wrap;gap:.35rem;}
  .chip{background:var(--usuarios-primary-soft);border-radius:999px;padding:.2rem .7rem;color:var(--usuarios-primary);font-size:.78rem;font-weight:700;border:1px solid var(--usuarios-border);animation:fadeIn .6s ease both;}
  .help{font-size:.82rem;color:var(--usuarios-muted);margin-top:6px;}
  .input,.select{width:100%;height:46px;border-radius:14px;border:1.5px solid var(--usuarios-border);background:#ffffff;color:var(--usuarios-dark);padding:0 1rem;font-weight:600;transition:border .15s,box-shadow .15s;}
  .input:focus,.select:focus{outline:none;border-color:var(--usuarios-primary);box-shadow:0 0 0 5px rgba(29,78,216,.16);}
  .actions{display:flex;gap:.5rem;justify-content:flex-end;flex-wrap:wrap;}
  .btn{display:inline-flex;gap:.45rem;align-items:center;border:0;border-radius:14px;padding:.65rem 1rem;font-weight:700;cursor:pointer;transition:transform .05s,filter .2s,box-shadow .2s;}
  .btn:active{transform:translateY(1px) scale(.995);}
  .btn-primary{background:var(--usuarios-primary);color:#fff;box-shadow:0 18px 28px rgba(29,78,216,.22);}
  .btn-outline{background:#fff;border:1.5px solid var(--usuarios-border);color:var(--usuarios-dark);transition:border .2s,color .2s,background .2s;}
  .btn-outline:hover{background:var(--usuarios-primary-soft);color:var(--usuarios-primary);border-color:var(--usuarios-primary);}
  .alert{padding:1rem 1.2rem;border-radius:16px;margin:1.2rem 2rem 0;font-weight:600;border:1px solid transparent;}
  .alert-success{background:#ecfdf5;border-color:#bbf7d0;color:#047857;}
  .alert-error{background:#fff0f2;border-color:#fecdd3;color:#9f1239;}
  .users-card__footer{display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;align-items:center;padding:1rem 2rem 1.4rem;color:var(--usuarios-muted);font-weight:600;}
  tbody tr:hover td{background:var(--usuarios-primary-soft);}
  @media (max-width:960px){
    table.users thead{display:none;}
    table.users{border-spacing:0 16px;padding:1.2rem;}
    table.users tbody tr{display:block;}
    table.users tbody tr td{display:flex;justify-content:space-between;align-items:center;padding:12px 14px;}
    table.users tbody tr td::before{content:attr(data-label);font-weight:700;color:var(--usuarios-muted);margin-right:16px;text-transform:uppercase;font-size:.75rem;}
    .actions{justify-content:flex-start;width:100%;}
  }
</style>
@endpush
@section('main')
@php
  $collection = $users->getCollection();
  $resumenRoles = $collection->groupBy(fn($item) => optional($item->roles->first())->name ?? 'Sin rol')->map->count();
@endphp
<div class="users-page">
  <section class="intro">
    <div>
      <h2>Gestión de usuarios</h2>
      <p>Administra la información de doctores, pacientes y coordinadores desde un único lugar. Actualiza los datos y confirma con <strong>Guardar</strong>.</p>
    </div>
    <div class="intro-badges">
      <span class="badge"><span class="material-symbols-outlined">group</span>Total: {{ $users->total() }}</span>
      <span class="badge" style="background:rgba(29,78,216,.12);border-color:rgba(29,78,216,.3);color:var(--usuarios-primary);"><span class="material-symbols-outlined">verified</span>Datos verificados</span>
    </div>
  </section>
  <section class="stats">
    @foreach($resumenRoles as $rolNombre => $cantidad)
      <article class="stat-card">
        <h3>{{ $cantidad }}</h3>
        <span><span class="material-symbols-outlined" style="color:var(--usuarios-primary);font-size:1.1rem;">shield_person</span> {{ ucfirst($rolNombre) }}</span>
      </article>
    @endforeach
  </section>
  <div class="users-card">
    <div class="users-card__header">
      <div>
        <h3>Usuarios registrados</h3>
        <span>Edita cualquier información y confirma para guardar los cambios.</span>
      </div>
      <div class="toolbar">
        <form method="GET" action="{{ url()->current() }}" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
          <input class="input" type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o correo">
          <button class="btn" type="submit"><span class="material-symbols-outlined">search</span> Buscar</button>
        </form>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-error">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="users">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Contacto</th>
          <th>Rol</th>
          <th>Especialidades</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
      @foreach($users as $u)
        @php
          $roleIdActual = optional($u->roles->first())->id;
          $roleNombre = optional($u->roles->first())->name;
          $esAdmin = $u->roles->contains(fn($rr)=>$rr->name==='administrador');
          $espNombres = ($u->especialidades ?? collect())->pluck('nombre')->all();
        @endphp

        <form id="update-{{ $u->id }}" action="{{ route('admin.usuarios.update', $u) }}" method="POST">@csrf @method('PUT')</form>
        @unless($esAdmin)
          <form id="delete-{{ $u->id }}" action="{{ route('admin.usuarios.destroy', $u) }}" method="POST">@csrf @method('DELETE')</form>
        @endunless

        <tr>
          <td data-label="Usuario">
            <div class="user-name">
              <input class="input" form="update-{{ $u->id }}" type="text" name="name"
                     value="{{ old('name_'.$u->id, $u->name) }}" required>
            </div>
            <div class="user-id">ID: {{ $u->id }}</div>
          </td>

          <td data-label="Contacto">
            <input class="input" form="update-{{ $u->id }}" type="email" name="email"
                   value="{{ old('email_'.$u->id, $u->email) }}" required>
          </td>

          <td data-label="Rol">
            @if($esAdmin)
              <span class="chip" title="No editable para administradores">Administrador</span>
              <input type="hidden" form="update-{{ $u->id }}" name="role_id" value="{{ $roleIdActual }}">
            @else
              <select class="select" form="update-{{ $u->id }}" name="role_id" required>
                @foreach($roles->whereNotIn('name',['administrador']) as $r)
                  <option value="{{ $r->id }}" @selected($roleIdActual===$r->id)>{{ ucfirst($r->name) }}</option>
                @endforeach
              </select>
            @endif
          </td>

          <td data-label="Especialidades">
            @if(count($espNombres))
              <div class="chips">
                @foreach($espNombres as $n)
                  <span class="chip">{{ $n }}</span>
                @endforeach
              </div>
            @else
              <div class="help">—</div>
            @endif
          </td>

          <td data-label="Acciones">
            <div class="actions">
              <button form="update-{{ $u->id }}" type="submit" class="btn btn-primary">
                <span class="material-symbols-outlined">save</span> Guardar
              </button>
              @unless($esAdmin)
                <button form="delete-{{ $u->id }}" type="submit" class="btn btn-outline"
                        onclick="return confirm('¿Eliminar usuario {{ $u->name }}?');">
                  <span class="material-symbols-outlined">delete</span>
                </button>
              @endunless
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div class="users-card__footer">
      <div>
        @if ($users->hasPages())
          Página {{ $users->currentPage() }} de {{ $users->lastPage() }}
        @else
          Mostrando {{ $users->count() }} registros
        @endif
      </div>
      {!! $users->withQueryString()->links() !!}
    </div>
  </div>
</div>
@endsection
