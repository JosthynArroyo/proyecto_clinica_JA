<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Receta médica</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#111827;line-height:1.5;">
  <h2 style="margin-bottom:4px;">
    {{ $motivo === 'actualizacion' ? 'Actualización de receta médica' : 'Nueva receta médica' }}
  </h2>

  <p>Estimado/a {{ $cita->paciente->name ?? 'paciente' }},</p>

  <p>
    Adjuntamos su 
    {{ $motivo === 'actualizacion' ? 'receta actualizada' : 'nueva receta' }} 
    correspondiente a la cita con el Dr(a). 
    <strong>{{ $cita->doctor->name ?? '—' }}</strong> 
    del día {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} 
    a las {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}.
  </p>

  <p>Por favor, siga las indicaciones médicas y conserve este documento para futuras consultas.</p>

  <p style="margin-top:24px;">— Clínica Don Bosco</p>
</body>
</html>
