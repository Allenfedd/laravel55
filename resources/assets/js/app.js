import Vue from 'vue'
import axios from 'axios'
import router from './router'
import store from './store'
import toastr from 'toastr'
import 'toastr/toastr.scss'
import './http'

Vue.prototype.$http = axios;

// //Sweetalert2
window.swal = require('sweetalert2');

//Toastr
window.toastr = toastr;
window.toastr.options = {
    positionClass: "toast-bottom-right",
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "3000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

const app = new Vue({
    router,
    store
}).$mount('#app');
