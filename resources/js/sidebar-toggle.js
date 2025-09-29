const ready = (fn) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fn, { once: true });
  } else {
    fn();
  }
};

ready(() => {
  const sideMenu = document.querySelector('aside');
  const menuBtn = document.querySelector('#menu_bar');
  const closeBtn = sideMenu?.querySelector('.close');

  const showSidebar = () => sideMenu?.style.setProperty('display', 'block');
  const hideSidebar = () => sideMenu?.style.setProperty('display', 'none');

  menuBtn?.addEventListener('click', (event) => {
    event.preventDefault();
    showSidebar();
  });

  closeBtn?.addEventListener('click', (event) => {
    event.preventDefault();
    hideSidebar();
  });

  const mq = window.matchMedia('(min-width: 769px)');
  const resetSidebar = () => sideMenu?.style.removeProperty('display');

  mq.addEventListener?.('change', (event) => {
    if (event.matches) {
      resetSidebar();
    }
  });

  mq.addListener?.((event) => {
    if (event.matches) {
      resetSidebar();
    }
  });
});
