import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import CodeTool from '@editorjs/code';
import Table from '@editorjs/table';
import ImageTool from '@editorjs/image';
import Embed from '@editorjs/embed';
import Hyperlink from 'editorjs-hyperlink';

function initEditor(data, holder) {
  const editor = new EditorJS({
    holder: holder,
    data: data,
    logLevel: 'ERROR',
    readOnly: true,
    tools: {
      header: {
        class: Header
      },
      list: {
        class: List
      },
      image: {
        class: ImageTool
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
      }
    },
  });
}

export {
  initEditor
}
