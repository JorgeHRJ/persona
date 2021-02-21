import { initEditor } from "../modules/editor";

function init() {
  const holder = document.querySelector('[data-component="post-show"]');
  if (holder) {

    const data = holder.dataset.content ? JSON.parse(holder.dataset.content) : {};
    initEditor(data, holder);
  }
}

export default init;
