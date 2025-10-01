(function () {
  const form   = document.querySelector('form[data-endpoint-template]');
  if (!form) return;

  const espSel = document.getElementById('especialidad_id');
  const docSel = document.getElementById('doctor_id');
  const fecha  = document.getElementById('fecha');
  const hora   = document.getElementById('hora');

  // ---- NUEVO: elementos para mostrar tarifa
  const tarifaPanel = document.getElementById('tarifaPanel');
  const tarifaLabel = document.getElementById('tarifaLabel');
  const tarifaTemplate = form.dataset.tarifaTemplate || ''; // e.g. /api/tarifa/doctor/DOC_ID

  const endpointTemplate = form.dataset.endpointTemplate;
  const oldEsp = form.dataset.oldEsp;
  const oldDoc = form.dataset.oldDoc;

  // ===================== TARIFA (NUEVO) =====================
  const tarifaUrlFrom = (doctorId) => {
    return (tarifaTemplate || '').replace('DOC_ID', String(doctorId));
  };

  function hideTarifa() {
    if (tarifaPanel) {
      tarifaPanel.hidden = true;
      tarifaPanel.classList.remove('is-warning');
    }
    if (tarifaLabel) tarifaLabel.textContent = 'Tarifa: —';
  }

  async function fetchTarifa(doctorId) {
    if (!doctorId || !tarifaTemplate || !tarifaPanel || !tarifaLabel) {
      hideTarifa();
      return;
    }
    try {
      tarifaPanel.hidden = false;
      tarifaPanel.classList.remove('is-warning');
      tarifaLabel.textContent = 'Consultando tarifa…';

      const res = await fetch(tarifaUrlFrom(doctorId), { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const data = await res.json();
      if (data.ok && data.definido && data.label) {
        tarifaLabel.textContent = data.label; // "Tarifa: $XX.XX USD"
        tarifaPanel.classList.remove('is-warning');
      } else {
        tarifaLabel.textContent = 'Tarifa no configurada';
        tarifaPanel.classList.add('is-warning');
      }
    } catch (e) {
      console.error(e);
      tarifaLabel.textContent = 'Error al consultar la tarifa';
      tarifaPanel.classList.add('is-warning');
      tarifaPanel.hidden = false;
    }
  }
  // =========================================================

  async function loadDoctors(especialidadId, preselectId) {
    hideTarifa(); // al cambiar especialidad, reiniciar panel de tarifa
    docSel.innerHTML = '<option value="">Cargando…</option>';
    docSel.disabled = true;

    if (!especialidadId) {
      docSel.innerHTML = '<option value="">Seleccione una especialidad primero</option>';
      return;
    }

    const url = endpointTemplate.replace('ESP_ID', encodeURIComponent(especialidadId));

    try {
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const data = await res.json();
      if (!Array.isArray(data) || data.length === 0) {
        docSel.innerHTML = '<option value="">No hay doctores activos en esta especialidad</option>';
      } else {
        let opts = '<option value="">Seleccionar</option>';
        for (const d of data) {
          const sel = String(preselectId || '') === String(d.id) ? ' selected' : '';
          opts += `<option value="${d.id}"${sel}>${d.name}</option>`;
        }
        docSel.innerHTML = opts;

        // Si hay preselección (old value), consultar tarifa inmediatamente
        if (preselectId) {
          await fetchTarifa(preselectId);
        } else {
          hideTarifa();
        }
      }
      docSel.disabled = false;
    } catch (e) {
      console.error(e);
      docSel.innerHTML = '<option value="">Error cargando doctores</option>';
    }
  }

  function pad(n) { return String(n).padStart(2, '0'); }

  function updateMinTime() {
    try {
      if (!fecha.value) { hora.removeAttribute('min'); return; }
      const now = new Date();
      const chosen = new Date(fecha.value + 'T00:00:00');

      if (chosen.toDateString() === now.toDateString()) {
        const t = new Date(now.getTime() + 30 * 60000);
        const minVal = `${pad(t.getHours())}:${pad(t.getMinutes())}`;
        hora.min = minVal;
        if (hora.value && hora.value < minVal) hora.value = minVal;
      } else {
        hora.removeAttribute('min');
      }
    } catch (e) {}
  }

  fecha && fecha.addEventListener('change', updateMinTime);
  updateMinTime();

  espSel && espSel.addEventListener('change', function () {
    loadDoctors(this.value, null);
  });

  // ---- NUEVO: al cambiar doctor, consultar tarifa
  docSel && docSel.addEventListener('change', function () {
    const id = this.value;
    if (!id) {
      hideTarifa();
      return;
    }
    fetchTarifa(id);
  });

  // Restaurar estado si hay old values
  if (oldEsp) {
    loadDoctors(oldEsp, oldDoc || null);
  }
})();
