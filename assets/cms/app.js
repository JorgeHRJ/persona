import './css/app.scss';

// Stimulus app
//import './bootstrap';

// modules
import initBootstrap from './js/modules/bootstrap';
import initSidebar from './js/modules/sidebar';
import initNotyf from './js/modules/notyf';
import initEditor from './js/modules/editor';
import initDropzone from './js/modules/dropzone';
import { initDatepickerComponent } from './js/modules/datepicker';

initBootstrap();
initSidebar();
initNotyf();
initEditor();
initDropzone();
initDatepickerComponent();
