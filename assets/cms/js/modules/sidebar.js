import SimpleBar from "simplebar";

let simplebar = null;

function init() {
  const simpleBarElement = document.querySelector('[data-component="simplebar"]');

  if (simpleBarElement) {
    simplebar = new SimpleBar(simpleBarElement)

    const sidebarElement = document.querySelector('.sidebar');
    const sidebarToggleElement = document.querySelector('.sidebar-toggle');

    sidebarToggleElement.addEventListener('click', () => {
      sidebarElement.classList.toggle('collapsed');
      sidebarElement.addEventListener('transitionend', () => {
        window.dispatchEvent(new Event('resize'));
      });
    });
  }
}

export default init;
