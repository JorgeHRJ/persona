// https://github.com/editor-js/awesome-editorjs
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import CodeTool from '@editorjs/code';
import Table from '@editorjs/table';
import ImageTool from '@editorjs/image';
import Embed from '@editorjs/embed';
import Hyperlink from 'editorjs-hyperlink';

function initEditor(textarea) {
  console.log(textarea.dataset);
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
      },
      image: {
        class: ImageTool,
        config: {
          endpoints: {
            byFile: textarea.dataset.uploadimage,
          }
        }
      },
      embed: {
        class: Embed,
        config: {
          services: {
            youtube: true,
            codepen: true,
            twitter: true
          }
        }
      },
      table: {
        class: Table,
      },
      code: {
        class: CodeTool,
      },
      hyperlink: {
        class: Hyperlink,
        config: {
          shortcut: 'CMD+L',
          target: '_blank',
          rel: 'nofollow',
          availableTargets: ['_blank', '_self'],
          availableRels: ['author', 'noreferrer'],
          validate: false,
        }
      }
    },
    i18n: {
      toolNames: {
        Hyperlink: 'Link'
      },
      tools: {
        hyperlink: {
          'Save': 'Guardar',
          'Select target': 'Target',
          'Select rel': 'Rel'
        }
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
