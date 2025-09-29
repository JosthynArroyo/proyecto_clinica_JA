<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación de Cita</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    /* === Reset & base compatibles con email === */
    body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
    table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
    img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; }
    table { border-collapse:collapse !important; }
    body { margin:0 !important; padding:0 !important; width:100% !important; }

    /* === Estilos generales === */
    .bg-light { background:#f5f7fb; }
    .card { width:100%; max-width:640px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:14px; box-shadow:0 6px 18px rgba(0,0,0,.06); }
    .header { background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#ffffff; text-align:center; padding:26px 20px; }
    .brand { margin:0; font:700 20px/1.2 system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; letter-spacing:.3px; }
    .content { padding:28px 26px; color:#1f2937; font:500 15px/1.6 system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; }
    .h2 { margin:0 0 10px; font-weight:800; font-size:20px; color:#111827; }
    .muted { color:#6b7280; }
    .details { background:#f3f4f6; border:1px solid #e5e7eb; border-radius:12px; padding:14px 16px; }
    .row { width:100%; }
    .cell { vertical-align:top; padding:8px 4px; }
    .label { font-weight:700; color:#374151; }
    .cta { display:inline-block; margin-top:16px; padding:12px 18px; background:#2563eb; color:#ffffff !important; text-decoration:none; border-radius:10px; font-weight:800; }
    .divider { height:1px; background:#eef2f7; margin:22px 0; }
    .footer { text-align:center; color:#6b7280; font:500 12px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; padding:18px 16px; }

    /* === Responsivo simple === */
    @media (max-width:520px) {
      .content { padding:22px 18px; }
      .brand { font-size:18px; }
      .h2 { font-size:18px; }
    }
  </style>
</head>
<body class="bg-light">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#f5f7fb" style="padding:22px 12px;">
    <tr>
      <td align="center">
        <table class="card" role="presentation" cellpadding="0" cellspacing="0">
          <!-- Header -->
          <tr>
            <td class="header">
              <p class="brand">Clínica Don Bosco</p>
              <div style="margin-top:6px; font:600 13px/1.4 system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; opacity:.9;">
                Confirmación de cita médica
              </div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="content">
              <p class="h2">¡Hola {{ $cita->paciente->name }}!</p>
              <p>Tu cita médica ha sido <strong>confirmada</strong>. A continuación te compartimos el resumen:</p>

              <!-- Detalles -->
              <table role="presentation" width="100%" class="details" cellpadding="0" cellspacing="0">
                <!-- Doctor -->
                <tr class="row">
                  <td class="cell" width="28" valign="top">
                    <!-- person icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1d4ed8" xmlns="http://www.w3.org/2000/svg">
                      <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
                    </svg>
                  </td>
                  <td class="cell">
                    <div class="label">Doctor</div>
                    <div>{{ $cita->doctor->name }}</div>
                  </td>
                </tr>

                <!-- Especialidad -->
                <tr class="row">
                  <td class="cell" width="28" valign="top">
                    <!-- local_hospital icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0ea5e9" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19 3H5a2 2 0 0 0-2 2v14h6v-4h6v4h6V5a2 2 0 0 0-2-2Zm-4 7h-2v2h-2v-2H9V8h2V6h2v2h2v2Z"/>
                    </svg>
                  </td>
                  <td class="cell">
                    <div class="label">Especialidad</div>
                    <div>{{ $cita->especialidad->nombre }}</div>
                  </td>
                </tr>

                <!-- Fecha -->
                <tr class="row">
                  <td class="cell" width="28" valign="top">
                    <!-- calendar_month icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#16a34a" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 14H5V10h14v8Zm0-10H5V6h14v2Z"/>
                    </svg>
                  </td>
                  <td class="cell">
                    <div class="label">Fecha</div>
                    <div>{{ $cita->fecha->format('d/m/Y') }}</div>
                  </td>
                </tr>

                <!-- Hora -->
                <tr class="row">
                  <td class="cell" width="28" valign="top">
                    <!-- schedule icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b" xmlns="http://www.w3.org/2000/svg">
                      <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 11h-4V7h2v4h2v2Z"/>
                    </svg>
                  </td>
                  <td class="cell">
                    <div class="label">Hora</div>
                    <div>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</div>
                  </td>
                </tr>
              </table>

              <!-- Botón (único) -->
              @php
                // Usar una sola URL. Si no hay sesión, el middleware redirige a "/?login=1" y tu JS abre el modal.
                $urlCitas = route('paciente.citas');
              @endphp
              <p style="margin:18px 0 0;">
                <a href="{{ $urlCitas }}" class="cta">Ver mis citas</a>
              </p>

              <div class="divider"></div>

              <p class="muted" style="margin:0;">
                ¿Necesitas reprogramar o cancelar? Responde a este correo o realiza el cambio desde tu panel.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td class="footer">
              © {{ date('Y') }} Clínica Don Bosco · Todos los derechos reservados<br>
              <span class="muted">Este es un correo automático, por favor no responder.</span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
