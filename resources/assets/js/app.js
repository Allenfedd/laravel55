import Vue from 'vue'
import axios from 'axios'
import router from './router'
import store from './store'
import './http'

Vue.prototype.$http = axios;

const app = new Vue({
    router,
    store
}).$mount('#app');
