import { Tooltip, Popover, Dropdown, Alert, Collapse } from "bootstrap";

function initPopovers() {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  popoverTriggerList.map((popoverTriggerEl) => {
    return new Popover(popoverTriggerEl)
  })
}

function initTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map((tooltipTriggerEl) => {
    return new Tooltip(tooltipTriggerEl)
  })
}

function initDropdown() {
  const dropdownTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
  const dropdownList = dropdownTriggerList.map((dropdownTriggerEl) => {
    return new Dropdown(dropdownTriggerEl);
  });
}

function initCollapse() {
  const collapseTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="collapse"]'));
  const collapseList = collapseTriggerList.map((collapseTriggerEl) => {
    return new Collapse(collapseTriggerEl);
  });
}

function initAlert() {
  const alertTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="alert"]'));
  const alertList = alertTriggerList.map((alertTriggerEl) => {
    return new Alert(alertTriggerEl);
  });
}

function init() {
  initAlert();
  initCollapse();
  initDropdown();
  initTooltips();
  initPopovers();
}

export default init;
