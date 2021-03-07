import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Table from '@editorjs/table';
import ImageTool from '@editorjs/image';
import Embed from '@editorjs/embed';
import Hyperlink from 'editorjs-hyperlink';
import CodeTool from '@editorjs/code';

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
      code: CodeTool,
      hyperlink: {
        class: Hyperlink,
      }
    },
  });
}

export {
  initEditor
}
