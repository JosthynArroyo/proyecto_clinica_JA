<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendar cita</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  @vite('resources/css/pages/contacto.css')
  @vite('resources/js/pages/contacto.js')
</head>
<body>
  <main class="wrap">
    <a href="{{ url('/') }}" class="btn-back">← Regresar</a>

    <section class="card">
      <h1>Agendar cita como invitado</h1>
      <p class="sub">No necesitas cuenta. Al final puedes elegir crear una.</p>

      @if(session('success'))
        <div class="alert ok">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert warn">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('contacto.guest.store') }}" id="guest-form" novalidate>
        @csrf
        <div class="grid">
          <div class="field">
            <label for="full_name">Nombre completo</label>
            <input id="full_name" name="full_name" type="text" required maxlength="120" value="{{ old('full_name') }}">
          </div>

          <div class="field">
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}">
          </div>

          <div class="field">
            <label for="phone">Teléfono (WhatsApp opcional)</label>
            <input id="phone" name="phone" type="tel" inputmode="tel" pattern="[\d\s\-\+\(\)]{7,20}" placeholder="+1 555 555 5555" value="{{ old('phone') }}">
            <small class="hint">Se usará para recordatorios.</small>
          </div>

          <div class="field">
            <label for="service">Servicio</label>
            <select id="service" name="service" required>
              <option value="" disabled selected>Selecciona…</option>
              <option {{ old('service')==='Medicina general'?'selected':'' }}>Medicina general</option>
              <option {{ old('service')==='Odontología'?'selected':'' }}>Odontología</option>
              <option {{ old('service')==='Pediatría'?'selected':'' }}>Pediatría</option>
              <option {{ old('service')==='Ginecología'?'selected':'' }}>Ginecología</option>
            </select>
          </div>

          <div class="field">
            <label for="date">Fecha preferida</label>
            <input id="date" name="date" type="date" required min="">
          </div>

          <div class="field">
            <label for="time">Hora preferida</label>
            <select id="time" name="time" required>
              <option value="" disabled selected>Selecciona…</option>
            </select>
          </div>

          <div class="field full">
            <label for="notes">Motivo / notas</label>
            <textarea id="notes" name="notes" rows="4" maxlength="800" placeholder="Describe brevemente el motivo de la consulta">{{ old('notes') }}</textarea>
          </div>
        </div>

        <div class="divider"></div>

        <fieldset class="optin">
          <label class="check">
            <input id="want_account" type="checkbox" name="want_account" value="1">
            <span>Quiero crear una cuenta para seguir mis citas</span>
          </label>

          <div id="account-panel" hidden>
            <div class="grid">
              <div class="field">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" minlength="8" autocomplete="new-password">
                <small class="hint" id="pw-hint">Mínimo 8 caracteres.</small>
              </div>

              <div class="field">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
              </div>
            </div>
          </div>
        </fieldset>

        <label class="check mt">
          <input type="checkbox" name="consent" required>
          <span>Acepto recibir recordatorios por correo y/o WhatsApp para esta cita.</span>
        </label>

        <button type="submit" class="btn-primary">Enviar solicitud</button>

        <p class="alt">
          ¿Ya tienes cuenta?
          <a href="{{ route('login') }}">Inicia sesión</a>
          para agendar más rápido.
        </p>
      </form>
    </section>
  </main>
</body>
</html>
