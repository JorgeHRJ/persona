import './css/app.scss';

import initBootstrap from './js/modules/bootstrap';
import initTheme from './js/modules/theme';

import initPostController from './js/controllers/post_controller';

initBootstrap();
initTheme();

if (document.querySelector('[data-controller="post"]')) {
  initPostController();
}
