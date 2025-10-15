(function () {
  const form = document.querySelector('form[data-endpoint-template]');
  if (!form) return;

  const espSel   = document.getElementById('especialidad_id');
  const docSel   = document.getElementById('doctor_id');
  const fechaInp = document.getElementById('fecha');
  const horaSel  = document.getElementById('hora');
  const horaHelp = document.getElementById('horaHelp');

  const tarifaPanel = document.getElementById('tarifaPanel');
  const tarifaLabel = document.getElementById('tarifaLabel');

  const doctorsUrlTpl = form.dataset.endpointTemplate;
  const tarifaUrlTpl  = form.dataset.tarifaTemplate;
  const slotsUrlTpl   = form.dataset.slotsTemplate; // debe contener DOC_ID y FECHA

  const oldEsp  = form.dataset.oldEsp || '';
  const oldDoc  = form.dataset.oldDoc || '';
  const oldHora = form.dataset.oldHora || '';

  const tarifaUrlFrom = (doctorId) => (tarifaUrlTpl || '').replace('DOC_ID', String(doctorId));
  const slotsUrlFrom  = (doctorId, fecha) =>
    (slotsUrlTpl || '').replace('DOC_ID', String(doctorId)).replace('FECHA', encodeURIComponent(fecha));

  function hideTarifa(){
    if (tarifaPanel) tarifaPanel.hidden = true;
    if (tarifaLabel) tarifaLabel.textContent = 'Tarifa: —';
  }

  async function fetchTarifa(doctorId){
    if(!doctorId || !tarifaUrlTpl || !tarifaPanel || !tarifaLabel){ hideTarifa(); return; }
    try{
      tarifaPanel.hidden=false;
      tarifaPanel.classList.remove('is-warning');
      tarifaLabel.textContent='Consultando tarifa…';
      const res = await fetch(tarifaUrlFrom(doctorId), { headers:{'Accept':'application/json'} });
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();
      if(data.ok && data.definido && data.label){
        tarifaLabel.textContent = data.label;
      }else{
        tarifaLabel.textContent = 'Tarifa no configurada';
        tarifaPanel.classList.add('is-warning');
      }
    }catch{
      tarifaLabel.textContent='Error al consultar la tarifa';
      tarifaPanel.classList.add('is-warning');
      tarifaPanel.hidden=false;
    }
  }

  async function loadDoctors(especialidadId, preselectId){
    hideTarifa();
    clearSlots();
    docSel.innerHTML='<option value="">Cargando…</option>';
    docSel.disabled=true;
    if(!especialidadId){
      docSel.innerHTML='<option value="">Seleccione una especialidad primero</option>';
      return;
    }
    const url = doctorsUrlTpl.replace('ESP_ID', encodeURIComponent(especialidadId));
    try{
      const res = await fetch(url, { headers:{'Accept':'application/json'} });
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();
      if(!Array.isArray(data) || data.length===0){
        docSel.innerHTML='<option value="">No hay doctores activos en esta especialidad</option>';
      }else{
        let opts='<option value="">Seleccionar</option>';
        for(const d of data){
          const sel = String(preselectId||'')===String(d.id)?' selected':'';
          opts += `<option value="${d.id}"${sel}>${d.name}</option>`;
        }
        docSel.innerHTML=opts;
      }
      docSel.disabled=false;
      if (preselectId) { await fetchTarifa(preselectId); await loadSlots(); }
    }catch{
      docSel.innerHTML='<option value="">Error cargando doctores</option>';
      docSel.disabled=false;
    }
  }

  function clearSlots(msg){
    horaSel.innerHTML = `<option value="">${msg || 'Seleccione doctor y fecha'}</option>`;
    horaSel.disabled = true;
  }

  async function loadSlots(){
    const doctorId = docSel.value;
    const fecha    = fechaInp.value; // type="date" -> YYYY-MM-DD
    if(!doctorId || !fecha){ clearSlots(); return; }

    horaHelp && (horaHelp.textContent = 'Buscando horarios…');
    clearSlots('Buscando…');

    try{
      const url = slotsUrlFrom(doctorId, fecha);
      const res = await fetch(url, { headers:{'Accept':'application/json'} });
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();

      const slots = Array.isArray(data.slots) ? data.slots : [];
      if(slots.length===0){
        clearSlots('No hay horarios disponibles para ese día');
        horaHelp && (horaHelp.textContent = 'No hay horarios disponibles para ese día.');
        return;
      }

      let opts = '<option value="">Seleccionar hora</option>';
      for(const h of slots){
        const sel = String(oldHora || '') === String(h) ? ' selected' : '';
        opts += `<option value="${h}"${sel}>${h}</option>`;
      }
      horaSel.innerHTML = opts;
      horaSel.disabled = false;
      horaHelp && (horaHelp.textContent = 'Formato 24h. Se listan solo los horarios disponibles.');
    }catch{
      clearSlots('Error al cargar horarios');
      horaHelp && (horaHelp.textContent = 'Error al cargar horarios.');
    }
  }

  espSel && espSel.addEventListener('change', function(){ loadDoctors(this.value, null); });
  docSel && docSel.addEventListener('change', function(){
    const id=this.value;
    if(!id){ hideTarifa(); clearSlots(); return; }
    fetchTarifa(id);
    loadSlots();
  });
  fechaInp && fechaInp.addEventListener('change', loadSlots);

  (async function init(){
    if (oldEsp) {
      await loadDoctors(oldEsp, oldDoc || null);
      if (oldDoc && fechaInp && fechaInp.value) await loadSlots();
    }
  })();
})();
