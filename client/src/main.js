import Vue from 'vue';
import App from './App.vue';
import router from './router';
import axios from './plugins/axios'; // Importe o arquivo de configuração do Axios
import store from './store'; // Importe o Vuex, se estiver usando

Vue.config.productionTip = false;

Vue.prototype.$http = axios; // Configure o Axios como um plugin do Vue

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
