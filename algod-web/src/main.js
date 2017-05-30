import Vue from 'vue'
import App from './App'
import router from './router'

import Materialize from 'materialize-css';
import MaterializeSass from '../static/sass/materialize.scss';

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  render: h => h(App)
})
