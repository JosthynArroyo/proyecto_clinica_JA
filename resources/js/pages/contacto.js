(function () {
  const date = document.getElementById('date');
  const time = document.getElementById('time');
  const want = document.getElementById('want_account');
  const panel = document.getElementById('account-panel');
  const pw = document.getElementById('password');
  const pw2 = document.getElementById('password_confirmation');
  const hint = document.getElementById('pw-hint');

  // fecha mínima hoy
  const today = new Date();
  const iso = today.toISOString().slice(0,10);
  if (date) date.min = iso;

  // slots 08:00–17:30 cada 30 min
  function buildSlots() {
    if (!time) return;
    time.innerHTML = '<option value="" disabled selected>Selecciona…</option>';
    for (let h = 8; h <= 17; h++) {
      for (let m of [0,30]) {
        const label = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
        const opt = document.createElement('option');
        opt.value = label; opt.textContent = label;
        time.appendChild(opt);
      }
    }
  }
  buildSlots();

  // panel de creación de cuenta
  function togglePanel() {
    const on = want && want.checked;
    if (!panel) return;
    panel.hidden = !on;
    if (pw) pw.required = on;
    if (pw2) pw2.required = on;
  }
  want?.addEventListener('change', togglePanel);
  togglePanel();

  // retroalimentación de contraseña
  pw?.addEventListener('input', () => {
    const v = pw.value || '';
    const okLen = v.length >= 8;
    const okMix = /[A-Za-z]/.test(v) && /\d/.test(v);
    if (hint) hint.textContent = okLen && okMix
      ? 'Fuerte: combina letras y números.'
      : 'Mínimo 8 caracteres. Usa letras y números.';
  });

  // coincidencia antes de enviar
  document.getElementById('guest-form')?.addEventListener('submit', (e) => {
    if (!panel?.hidden && pw && pw2 && pw.value !== pw2.value) {
      e.preventDefault();
      alert('Las contraseñas no coinciden.');
      pw2.focus();
    }
  });
})();
