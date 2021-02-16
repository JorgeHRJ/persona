import Dropzone from 'dropzone/dist/dropzone';
import { initCropperFromUpload } from './cropper';

function initDropzone(input) {
  // create div to hold the dropzone
  const dropzoneDiv = document.createElement('div');
  dropzoneDiv.id = `dropzone-${input.id}`;
  dropzoneDiv.classList.add('dropzone');

  const placeholderDropzoneDiv = document.createElement('div');
  placeholderDropzoneDiv.innerText = 'Suelta el archivo aquí o haz click para elegirlo';
  placeholderDropzoneDiv.classList.add('dz-message');

  dropzoneDiv.appendChild(placeholderDropzoneDiv);

  input.parentElement.insertBefore(dropzoneDiv, input.nextSibling);

  // init Dropzone
  Dropzone.options.myAwesomeDropzone = false;
  Dropzone.autoDiscover = false;

  const newDropzone = new Dropzone(`#${dropzoneDiv.id}`, {
    url: input.dataset.url,
    complete: (file) => {
      if (file.xhr.status === 200) {
        const parsed = JSON.parse(file.xhr.response);
        initCropperFromUpload(input, parsed.data);
        newDropzone.removeAllFiles();
      }
    }
  });
}

function init() {
  const dropzones = document.querySelectorAll('[data-component="dropzone"]');
  if (dropzones) {
    dropzones.forEach(dropzone => initDropzone(dropzone));
  }
}

export default init;
