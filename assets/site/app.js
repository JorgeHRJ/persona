import './css/app.scss';

import initBootstrap from './js/modules/bootstrap';
import initTheme from './js/modules/theme';
import initLazyload from './js/modules/lazyload';

import initPostController from './js/controllers/post_controller';

initBootstrap();
initTheme();
initLazyload();

if (document.querySelector('[data-controller="post"]')) {
  initPostController();
}
