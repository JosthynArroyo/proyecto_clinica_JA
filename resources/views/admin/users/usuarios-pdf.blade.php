{{-- resources/views/admin/users/usuarios-pdf.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body{font-family:DejaVu Sans,Arial,sans-serif;font-size:12px;color:#0f172a}
    h3{margin:0 0 8px 0}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:6px}
    th{text-transform:uppercase;background:#f3f4f6}
  </style>
</head>
<body>
<h3>Reporte de Usuarios</h3>
<table>
  <thead>
    <tr>
      <th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Cédula</th><th>Rol</th><th>Estado</th><th>Suspendido hasta</th><th>Creado</th><th>Especialidades</th>
    </tr>
  </thead>
  <tbody>
  @foreach($users as $u)
    <tr>
      <td>{{ $u->id }}</td>
      <td>{{ $u->name }}</td>
      <td>{{ $u->email }}</td>
      <td>{{ $u->telefono }}</td>
      <td>{{ $u->dni }}</td>
      <td>{{ optional($u->roles->first())->name }}</td>
      <td>{{ $u->status ?? 'active' }}</td>
      <td>{{ $u->suspended_until?->format('Y-m-d H:i') }}</td>
      <td>{{ $u->created_at?->format('Y-m-d H:i') }}</td>
      <td>{{ ($u->especialidades?->pluck('nombre')->implode(', ')) ?: '' }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>
