import EasyMDE from 'easymde';

let imageData = null;

function getImage() {
  return new Promise(((resolve) => {
    const interval = setInterval(() => {
      const temp = imageData;
      if (temp != null) {
        imageData = null;
        clearInterval(interval);
        resolve(temp);
      }
    }, 10);
  }));
}

function uploadImage(event) {
  const input = event.currentTarget;

  const file = input.files[0];
  const url = input.dataset.url;

  return new Promise((resolve, reject) => {
    const httpRequest = new XMLHttpRequest();
    const formData = new FormData();
    formData.append('image', file);
    httpRequest.open('POST', url);
    httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    httpRequest.send(formData);
    httpRequest.onreadystatechange = () => {
      if (httpRequest.readyState === XMLHttpRequest.DONE) {
        if (httpRequest.status === 200) {
          resolve(JSON.parse(httpRequest.response));
        } else if (httpRequest.status === 500
          || httpRequest.status === 400
          || httpRequest.status === 403
          || httpRequest.status === 404) {
          reject(httpRequest.status);
        }
      }
    };
  });
}

function initEditor(textarea) {
  // create input to upload images
  const imageUploadInput = document.createElement('input');
  imageUploadInput.setAttribute('type', 'file');
  imageUploadInput.classList.add('hide');
  imageUploadInput.dataset.component = 'editor-insert-image';
  imageUploadInput.dataset.url = textarea.dataset.uploadimage;
  imageUploadInput.addEventListener('change', (event) => {
    uploadImage(event).then((responseImage) => { imageData = responseImage });
  });

  // add to the container
  textarea.parentElement.insertBefore(imageUploadInput, textarea.nextSibling);

  const editor = new EasyMDE(
    {
      element: textarea,
      status: ['lines', 'words', 'cursor'],
      autosave: {
        enabled: false,
      },
      spellChecker: false,
      nativeSpellcheck: true,
      previewRender: false,
      autoDownloadFontAwesome: false,
      hideIcons: ['image'],
      toolbar: [
        'bold', 'italic', 'heading', '|', 'undo', 'redo', '|', 'code', 'quote', 'unordered-list', 'ordered-list',
        {
          name: 'Insert Image',
          action: (editor) => {
            document.querySelector('[data-component="editor-insert-image"]').click();
            getImage().then((image) => {
              editor.codemirror.replaceSelection(`![${image.name}](${image.path})`);
            });
          },
          className: 'fa fa-image',
          title: 'Insert Image',
        },
        'link', 'preview', 'side-by-side',
      ],
    }
  );

  editor.codemirror.on('change', () => {
    textarea.value = editor.value();
  });
}

function init() {
  const editors = document.querySelectorAll('[data-component="editor"]');
  if (editors) {
    editors.forEach((editor) => {
      initEditor(editor);
    });
  }
}

export default init;
