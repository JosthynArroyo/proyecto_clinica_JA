(() => {
  const modal = document.getElementById('loginModal');
  if (!modal) return;

  const closeBtn = document.getElementById('closeLoginModal');
  const backdrop = modal.querySelector('.modal-backdrop');
  const triggers = document.querySelectorAll('[data-login-trigger]');

  let savedY = 0;

  function openModal(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    savedY = window.scrollY || 0;
    document.body.classList.add('modal-open');
    modal.classList.add('is-open');

    const first = modal.querySelector('input[name="email"]');
    if (first) setTimeout(() => first.focus(), 0);
  }

  function closeModal(e) {
    if (e) e.preventDefault();
    modal.classList.remove('is-open');
    document.body.classList.remove('modal-open');
    window.scrollTo(0, savedY);
  }

  triggers.forEach(el => el.addEventListener('click', openModal));
  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);

  document.addEventListener('keydown', ev => {
    if (ev.key === 'Escape' && modal.classList.contains('is-open')) closeModal(ev);
  });

  if (modal.querySelector('[data-open-login-onload]')) openModal();
})();
