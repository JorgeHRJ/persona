import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';

function initEditor(textarea) {
  // create div to hold the editor
  const editorDiv = document.createElement('div');
  editorDiv.id = `editor-${textarea.id}`;
  editorDiv.classList.add('mt-2');
  editorDiv.dataset.target = `#${textarea.id}`;

  // add to the container
  textarea.parentElement.insertBefore(editorDiv, textarea.nextSibling);

  // initialize editor
  const data = textarea.value ? JSON.parse(textarea.value) : {};
  let placeholder = textarea.getAttribute('placeholder');
  if (!placeholder) {
    placeholder = '';
  }

  const editor = new EditorJS({
    holder: editorDiv,
    data: data,
    placeholder: placeholder,
    logLevel: 'ERROR',
    tools: {
      header: {
        class: Header,
        inlineToolbar : true
      },
      list: {
        class: List,
        inlineToolbar: true
      }
    },
    onChange: (api) => {
      api.saver.save().then((data) => {
        textarea.value = JSON.stringify(data);
      });
    }
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
