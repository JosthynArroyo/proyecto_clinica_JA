<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva cita asignada</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f5f7fb; padding:20px;">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:auto; background:#fff; border:1px solid #e5e7eb; border-radius:12px;">
    <tr>
      <td style="background:#059669; color:#fff; padding:20px; text-align:center; border-radius:12px 12px 0 0;">
        <h2 style="margin:0;">Clínica Don Bosco</h2>
        <p style="margin:5px 0 0;">Nueva cita asignada en su agenda</p>
      </td>
    </tr>
    <tr>
      <td style="padding:24px; color:#1f2937;">
        <p><strong>Hola Dr(a). {{ $cita->doctor->name }}</strong>,</p>
        <p>Se ha registrado una nueva cita en su agenda. A continuación los detalles:</p>

        <ul style="padding-left:20px;">
          <li><strong>Paciente:</strong> {{ $cita->paciente->name }}</li>
          <li><strong>Especialidad:</strong> {{ $cita->especialidad->nombre }}</li>
          <li><strong>Fecha:</strong> {{ $cita->fecha->format('d/m/Y') }}</li>
          <li><strong>Hora:</strong> {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</li>
        </ul>

        <p style="margin-top:20px;">
          <a href="{{ route('doctor.citas') }}" style="display:inline-block; background:#059669; color:#fff; padding:12px 20px; text-decoration:none; border-radius:8px; font-weight:bold;">
            Ir a mi agenda
          </a>
        </p>
      </td>
    </tr>
    <tr>
      <td style="background:#f3f4f6; text-align:center; padding:12px; font-size:12px; color:#6b7280; border-radius:0 0 12px 12px;">
        © {{ date('Y') }} Clínica Don Bosco · Este es un correo automático, por favor no responder.
      </td>
    </tr>
  </table>
</body>
</html>

