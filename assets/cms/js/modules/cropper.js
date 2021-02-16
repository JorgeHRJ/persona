import Cropper from 'cropperjs';
import { Modal } from 'bootstrap';

let modal = null;
let cropper = null;
let input = null;
let image = null;
let width = null;
let height = null;

function updateInfo(requiredWidth, requiredHeight, currentWidth, currentHeight) {
  const requiredSpan = document.querySelector('[data-component="required-size"]');
  const cropSpan = document.querySelector('[data-component="crop-size"]');
  const errorSpan = document.querySelector('[data-component="crop-error"]');

  requiredSpan.innerText = `${requiredWidth}x${requiredHeight}`;
  cropSpan.innerText = `${currentWidth}x${currentHeight}`;
  if (currentWidth < requiredWidth || currentHeight < requiredHeight) {
    if (errorSpan.classList.contains('hide')) {
      errorSpan.classList.remove('hide');
    }
  } else {
    if (!errorSpan.classList.contains('hide')) {
      errorSpan.classList.add('hide');
    }
  }
}

function initCropper(element, minWidth, minHeight) {
  cropper = new Cropper(element, {
    aspectRatio: minWidth / minHeight,
    zoomable: false,
    mouseWheelZoom: false,
    rotatable: false,
    ready: () => {
      const cropData = cropper.getData();
      updateInfo(minWidth, minHeight, Math.round(cropData.width), Math.round(cropData.height));
    },
    cropend: () => {
      const cropData = cropper.getData();
      const newWidth = Math.round(cropData.width);
      const newHeight = Math.round(cropData.height);

      updateInfo(minWidth, minHeight, newWidth, newHeight);
    }
  });
}

function saveCropped() {
  const croppedCanvas = cropper.getCroppedCanvas();
  const data = croppedCanvas.toDataURL();

  const preview = document.querySelector(`[data-component="image-preview-${input.id}"]`);
  preview.src = data;
  if (preview.classList.contains('hide')) {
    preview.classList.remove('hide');
  }

  input.value = data;

  modal.hide();
}

function initModal() {
  const cropperModal = document.querySelector('[data-component="cropper-modal"]');
  if (!cropperModal) {
    return;
  }

  cropperModal.addEventListener('hidden.bs.modal', () => {
    cropper.destroy();
  });
  cropperModal.addEventListener('shown.bs.modal', () => {
    initCropper(image, width, height);
  });

  const saveButton = cropperModal.querySelector('[data-action="save-crop"]');
  saveButton.addEventListener('click', saveCropped);

  modal = new Modal(cropperModal);
}

function initCropperFromUpload(uploadInput, data) {
  if (cropper) {
    cropper.destroy();
  }

  image = document.querySelector('[data-component="image-cropper"]');
  image.src = data;

  const sizes = uploadInput.dataset.size.split('x');
  width = sizes[0];
  height = sizes[1];

  input = uploadInput;

  if (!modal) {
    initModal();
  }

  modal.show();
}

export {
  initCropperFromUpload
}
