import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';

function initEditor(element) {
  const textarea = document.querySelector(element.dataset.target);
  if (!textarea) {
    return;
  }

  const data = textarea.value ? JSON.parse(textarea.value) : {};

  const editor = new EditorJS({
    holder: element,
    data: data,
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
