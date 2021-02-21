import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';

function initEditor(data, holder) {
  const editor = new EditorJS({
    holder: holder,
    data: data,
    logLevel: 'ERROR',
    readOnly: true,
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
  });
}

export {
  initEditor
}
