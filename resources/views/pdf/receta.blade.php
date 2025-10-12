<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Receta Médica</title>
  <style>
    body{ font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#111827; }
    .box{ border:1px solid #e5e7eb; border-radius:12px; padding:18px; }
    .header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
    .title{ font-size:20px; font-weight:800; }
    .muted{ color:#6b7280; }
    h3{ margin:14px 0 8px; }
    .section{ margin-top:12px; }
    .small{ font-size:12px; }
    .divider{ height:1px; background:#e5e7eb; margin:12px 0; }
    .brand{ color:#1d4ed8; font-weight:800; }
  </style>
</head>
<body>
  <div class="box">
    <div class="header">
      <div>
        <div class="brand">Clínica Don Bosco</div>
        <div class="small muted">Receta médica</div>
      </div>
      <div class="small">
        <div><strong>Fecha:</strong> {{ $fechaPdf->format('d/m/Y H:i') }}</div>
        <div><strong>Doctor(a):</strong> {{ $cita->doctor->name ?? '—' }}</div>
        <div><strong>Paciente:</strong> {{ $cita->paciente->name ?? '—' }}</div>
      </div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <h3>Diagnóstico / Motivo</h3>
      <div style="white-space:pre-wrap">{{ $diagnostico }}</div>
    </div>

    <div class="section">
      <h3>Medicamentos</h3>
      <div style="white-space:pre-wrap">{{ $medicamentos }}</div>
    </div>

    @if(!empty($indicaciones))
    <div class="section">
      <h3>Indicaciones</h3>
      <div style="white-space:pre-wrap">{{ $indicaciones }}</div>
    </div>
    @endif

    <div class="divider"></div>

    <div class="small muted">
      <em>Documento generado automáticamente por el sistema. Ante cualquier duda consulte con su médico.</em>
    </div>
  </div>
</body>
</html>
