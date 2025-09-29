(() => {
  const modal = document.getElementById('loginModal');
  if (!modal) return;

  const closeBtn  = document.getElementById('closeLoginModal');
  const backdrop  = modal.querySelector('.modal-backdrop');
  const triggers  = document.querySelectorAll('[data-login-trigger]');
  const emailInput = modal.querySelector('input[name="email"]');

  let savedY = 0;

  function openModal(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    savedY = window.scrollY || 0;
    document.body.classList.add('modal-open');
    modal.classList.add('is-open');
    if (emailInput) setTimeout(() => emailInput.focus(), 0);
  }

  function closeModal(e) {
    if (e) e.preventDefault();
    modal.classList.remove('is-open');
    document.body.classList.remove('modal-open');
    window.scrollTo(0, savedY);
  }

  // Abrir desde links/botones con data-login-trigger
  triggers.forEach(el => el.addEventListener('click', openModal));

  // Cerrar con botón y con backdrop
  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);

  // Cerrar con ESC
  document.addEventListener('keydown', ev => {
    if (ev.key === 'Escape' && modal.classList.contains('is-open')) closeModal(ev);
  });

  // Abrir si hay errores de validación (marcado desde Blade)
  if (modal.querySelector('[data-open-login-onload]')) {
    openModal();
  }

  // Abrir si viene ?login=1 en la URL (desde el middleware)
  const params = new URLSearchParams(window.location.search);
  if (params.get('login') === '1') {
    openModal();
    // Limpiar el parámetro para no reabrir al refrescar
    params.delete('login');
    const cleanUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + window.location.hash;
    window.history.replaceState({}, '', cleanUrl);
  }

  // API simple para abrir desde JS externo si lo necesitas
  window.addEventListener('open-login-modal', openModal);
})();
