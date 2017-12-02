require('./bootstrap');

import router from './routes';
import store from './store';

var vm = new Vue({
  el: '#app',
  router,
  store
})
