@extends('layouts.admin')
@section('title','Usuarios | Admin')

@push('head')
<style>
  /* ======== LAYOUT DEL CONTENIDO ======== */
  .users-wrap{
    width:100%;
    margin: 96px clamp(16px,2vw,24px) 28px; /* lo bajé ~1cm aprox */
  }

  /* ======== CARD ======== */
  .card{
    width:100%;
    background:#fff;
    border:1px solid #d8e0ef;
    border-radius:20px;
    box-shadow:0 18px 32px rgba(15,23,42,.08);
    overflow:hidden;
  }
  .card-header{
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 18px;
    border-bottom:1px solid #e6ecf7;
    background:linear-gradient(180deg,#fafbff 0%, #ffffff 70%);
  }
  .card-header .title{ color:#64748b; font-weight:600 }
  .total-badge{
    background:#eef2ff; color:#4f46e5; border:1px solid #dbe2ff;
    border-radius:999px; padding:.25rem .6rem; font-weight:700
  }

  /* ======== TABLA: LÍNEAS RECTA + ANCHO REAL ======== */
  table.users{
    width:100%;
    border-collapse:collapse;    /* un solo borde: recto y limpio */
    table-layout:fixed;          /* respeta el colgroup */
    background:#fff;
  }
  /* proporciones suman 100% */
  table.users col.col-nombre{ width: 18%; }
  table.users col.col-correo{ width: 32%; }
  table.users col.col-rol{    width: 12%; }
  table.users col.col-esps{   width: 28%; }
  table.users col.col-acts{   width: 10%; }

  table.users thead th{
    background:#fafbff;
    color:#0f172a;
    font-weight:800;
    font-size:.95rem;
    text-align:left;
    padding:14px 12px;
    border:1.5px solid #d6dfef; /* MISMO borde en th/td → separadores perfectos */
    white-space:nowrap;
  }

  table.users tbody td{
    padding:12px;
    border:1.5px solid #d6dfef; /* MISMO borde */
    vertical-align:top;
    background:#fff;
  }

  /* Columna acciones centrada y con ancho mínimo suficiente */
  table.users thead th:nth-last-child(1),
  table.users tbody td:nth-last-child(1){
    text-align:center;
    min-width:160px;
  }

  /* ======== CONTROLES UNIFORMES ======== */
  .input,.select{
    width:100%; height:44px;
    border-radius:12px;
    background:#fbfcfe; color:#0f172a; padding:0 .85rem;
    border:1.6px solid #ccd5e5;
    line-height:44px;
  }
  .input:focus,.select:focus{
    outline:none; border-color:#7380ec; box-shadow:0 0 0 4px rgba(115,128,236,.18);
  }

  .chip{
    display:inline-block;
    margin:0 8px 8px 0;
    background:#eef2ff; border:1px solid #d5dcff;
    color:#3949ab; border-radius:999px; padding:.12rem .55rem;
    font-size:.8rem; font-weight:600;
    white-space:nowrap;
  }
  .help{ font-size:.82rem; color:#7a859f; margin-top:6px }

  .actions{ display:flex; justify-content:center; align-items:center; gap:.5rem }
  .btn{ display:inline-flex; gap:.45rem; align-items:center; border:0;
        border-radius:12px; padding:.65rem 1rem; font-weight:700; cursor:pointer }
  .btn-primary{ background:#7380ec; color:#fff }
  .btn-outline{ background:#fff; border:1.5px solid #e0e6f2; color:#424b5f }

  .alert{ padding:.85rem 1rem; border-radius:12px; margin:12px 16px 0; border:1px solid }
  .alert-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46 }
  .alert-error{ background:#fff1f2; border-color:#fecdd3; color:#9f1239 }

  .card-footer{
    display:flex; justify-content:flex-end; gap:.5rem; align-items:center;
    color:#7d8da1; padding:10px 16px 16px;
  }

  /* ======== HOVER / LEGIBILIDAD ======== */
  tbody tr:hover td{ background:#fbfdff }

  /* ======== RESPONSIVE ======== */
  @media (max-width: 980px){
    table.users col.col-esps{ width: 24% }
    table.users col.col-correo{ width: 36% }
  }
  @media (max-width: 780px){
    .card-header{ flex-direction:column; gap:.5rem; align-items:flex-start }
    thead{ display:none }
    table.users, tbody, tr, td{ display:block; width:100% }
    tbody tr{ border-top:1px solid #e6ecf7 }
    tbody td{ border-left:0; border-right:0 }
    tbody td::before{
      content: attr(data-label);
      display:block; font-weight:700; color:#334155; margin-bottom:6px
    }
    table.users col{ width:auto }
    .actions{ justify-content:flex-start }
  }

  /* Evita scroll horizontal accidental */
  html,body{ overflow-x:hidden; }
</style>
@endpush

@section('main')
<div class="users-wrap">

  <div class="card">
    <div class="card-header">
      <div class="title">Edita los datos y pulsa <b>Guardar</b>.</div>
      <span class="total-badge">Total: {{ $users->total() }}</span>
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
      <colgroup>
        <col class="col-nombre">
        <col class="col-correo">
        <col class="col-rol">
        <col class="col-esps">
        <col class="col-acts">
      </colgroup>

      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Especialidades</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
      @foreach($users as $u)
        @php
          $roleIdActual = optional($u->roles->first())->id;
          $esAdmin = $u->roles->contains(fn($rr)=>$rr->name==='administrador');
          $espNombres = ($u->especialidades ?? collect())->pluck('nombre')->all();
        @endphp

        <form id="update-{{ $u->id }}" action="{{ route('admin.usuarios.update', $u) }}" method="POST">@csrf @method('PUT')</form>
        @unless($esAdmin)
          <form id="delete-{{ $u->id }}" action="{{ route('admin.usuarios.destroy', $u) }}" method="POST">@csrf @method('DELETE')</form>
        @endunless

        <tr>
          <td data-label="Nombre">
            <input class="input" form="update-{{ $u->id }}" type="text" name="name"
                   value="{{ old('name_'.$u->id, $u->name) }}" required>
            <div class="help">ID: {{ $u->id }}</div>
          </td>

          <td data-label="Correo">
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
              @foreach($espNombres as $n)
                <span class="chip">{{ $n }}</span>
              @endforeach
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

    <div class="card-footer">
      @if ($users->hasPages())
        <div>Página {{ $users->currentPage() }} de {{ $users->lastPage() }}</div>
      @endif
      {!! $users->withQueryString()->links() !!}
    </div>
  </div>

</div>
@endsection
