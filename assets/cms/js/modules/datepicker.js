import flatpickr from "flatpickr";

function initDatepicker(element) {
  flatpickr(element, {
    allowInput: true,
    enableTime: true,
    dateFormat: "d/m/Y H:i",
  });
}

function initDatepickerComponent() {
  const datepickers = [].slice.call(document.querySelectorAll('[data-component="datepicker"]'));
  const datepickersList = datepickers.map((element) => {
    return initDatepicker(element);
  });
}

export {
  initDatepickerComponent,
  initDatepicker
};
