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
    --ok:#047857; --okbg:#ecfdf5; --okbd:#bbf7d0;
    --err:#9f1239; --errbg:#fff0f2; --errbd:#fecdd3;
    --warn:#92400e; --warnbg:#fffbeb; --warnbd:#fde68a;
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
  .chip.bad{color:#b91c1c;background:rgba(239,68,68,.12);}
  .chip.warn{color:#92400e;background:var(--warnbg);border-color:var(--warnbd);}
  .chip.ok{color:#065f46;background:#ecfdf5;border-color:#bbf7d0;}
  .help{font-size:.82rem;color:var(--usuarios-muted);margin-top:6px;}
  .input,.select{width:100%;height:46px;border-radius:14px;border:1.5px solid var(--usuarios-border);background:#ffffff;color:var(--usuarios-dark);padding:0 1rem;font-weight:600;transition:border .15s,box-shadow .15s;}
  .input:focus,.select:focus{outline:none;border-color:var(--usuarios-primary);box-shadow:0 0 0 5px rgba(29,78,216,.16);}
  .actions{display:flex;gap:.5rem;justify-content:flex-end;flex-wrap:wrap;}
  .btn{display:inline-flex;gap:.45rem;align-items:center;border:0;border-radius:14px;padding:.65rem 1rem;font-weight:700;cursor:pointer;transition:transform .05s,filter .2s,box-shadow .2s;}
  .btn:active{transform:translateY(1px) scale(.995);}
  .btn-primary{background:var(--usuarios-primary);color:#fff;box-shadow:0 18px 28px rgba(29,78,216,.22);}
  .btn-outline{background:#fff;border:1.5px solid var(--usuarios-border);color:var(--usuarios-dark);transition:border .2s,color .2s,background .2s;}
  .btn-outline:hover{background:var(--usuarios-primary-soft);color:var(--usuarios-primary);border-color:var(--usuarios-primary);}
  .btn-danger{background:#ef4444;color:#fff;}
  .btn-warn{background:#f59e0b;color:#111827;}
  .btn-ok{background:#10b981;color:#fff;}
  .alert{padding:1rem 1.2rem;border-radius:16px;margin:1.2rem 2rem 0;font-weight:600;border:1px solid transparent;}
  .alert-success{background:var(--okbg);border-color:var(--okbd);color:var(--ok);}
  .alert-error{background:var(--errbg);border-color:var(--errbd);color:var(--err);}
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
      <p>Administra la información y el estado de acceso: activo, suspendido, inactivo o bloqueado.</p>
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
        <span>Edita datos, rol y estado de cuenta. Confirma para guardar.</span>
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
          <th>Estado</th>
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
          <form id="block-{{ $u->id }}" action="{{ route('admin.usuarios.block', $u) }}" method="POST">@csrf @method('PATCH')</form>
          <form id="suspend-{{ $u->id }}" action="{{ route('admin.usuarios.suspend', $u) }}" method="POST">@csrf @method('PATCH')</form>
          <form id="activate-{{ $u->id }}" action="{{ route('admin.usuarios.activate', $u) }}" method="POST">@csrf @method('PATCH')</form>
          <form id="deactivate-{{ $u->id }}" action="{{ route('admin.usuarios.deactivate', $u) }}" method="POST">@csrf @method('PATCH')</form>
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

          <td data-label="Estado">
            @php
              $estado = $u->status ?? 'active';
              $isSusp = $u->suspended_until && now()->lt($u->suspended_until);
            @endphp
            <div class="chips">
              @if($estado==='blocked')
                <span class="chip bad" title="Sin acceso">Bloqueado</span>
              @elseif($estado==='inactive')
                <span class="chip warn" title="Inactivo por inactividad o manual">Inactivo</span>
              @elseif($isSusp)
                <span class="chip warn" title="Suspendido hasta {{ $u->suspended_until?->format('Y-m-d H:i') }}">Suspendido</span>
              @else
                <span class="chip ok" title="Con acceso">Activo</span>
              @endif
            </div>
            @unless($esAdmin)
              <div class="help">Último acceso: {{ $u->last_login_at?->diffForHumans() ?? '—' }}</div>
            @endunless
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

                @if(($u->status ?? 'active') !== 'blocked')
                  <button form="block-{{ $u->id }}" type="submit" class="btn btn-danger"
                          onclick="return confirm('Bloquear a {{ $u->name }} inmediatamente?');">
                    <span class="material-symbols-outlined">block</span>
                  </button>
                @endif

                @if(($u->status ?? 'active') !== 'inactive')
                  <button form="deactivate-{{ $u->id }}" type="submit" class="btn btn-outline"
                          onclick="return confirm('Marcar inactivo a {{ $u->name }}?');">
                    <span class="material-symbols-outlined">person_off</span>
                  </button>
                @endif

                <button type="button" class="btn btn-warn" onclick="openSuspend('{{ $u->id }}')">
                  <span class="material-symbols-outlined">hourglass</span>
                </button>

                @if(($u->status ?? 'active')!=='active' || ($u->suspended_until && now()->lt($u->suspended_until)))
                  <button form="activate-{{ $u->id }}" type="submit" class="btn btn-ok"
                          onclick="return confirm('Reactivar acceso de {{ $u->name }}?');">
                    <span class="material-symbols-outlined">verified</span>
                  </button>
                @endif
              @endunless
            </div>

            <!-- Campos ocultos para block/deactivate con motivo -->
            @unless($esAdmin)
              <input type="hidden" form="block-{{ $u->id }}" name="reason" value="Bloqueo manual">
              <input type="hidden" form="deactivate-{{ $u->id }}" name="reason" value="Inactivación manual">
              <input type="hidden" form="activate-{{ $u->id }}" name="reason" value="">
            @endunless
          </td>
        </tr>

        <!-- Modal simple de suspensión -->
        <tr id="susp-row-{{ $u->id }}" style="display:none;">
          <td colspan="6" style="padding-top:0;">
            <div style="margin:0 16px 16px;border:1px dashed var(--usuarios-border);border-radius:16px;padding:16px;background:#fff;">
              <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <div style="font-weight:700;">Suspender hasta:</div>
                <input class="input" form="suspend-{{ $u->id }}" type="datetime-local" name="until" required>
                <input class="input" form="suspend-{{ $u->id }}" type="text" name="reason" placeholder="Motivo (opcional)" style="flex:1;min-width:220px;">
                <button class="btn btn-warn" form="suspend-{{ $u->id }}" type="submit">
                  <span class="material-symbols-outlined">schedule</span> Confirmar suspensión
                </button>
                <button class="btn btn-outline" type="button" onclick="closeSuspend('{{ $u->id }}')">Cancelar</button>
              </div>
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

@push('scripts')
<script>
  function openSuspend(id){
    document.getElementById('susp-row-'+id).style.display='table-row';
  }
  function closeSuspend(id){
    document.getElementById('susp-row-'+id).style.display='none';
  }
</script>
@endpush
@endsection
