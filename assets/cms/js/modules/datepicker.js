import flatpickr from "flatpickr";

function initDatepicker(element) {
  const format = element.dataset.format ? element.dataset.format : 'd/m/Y H:i';
  const time = !!element.dataset.time;

  flatpickr(element, {
    allowInput: true,
    enableTime: time,
    dateFormat: format,
    monthSelectorType: 'static'
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
