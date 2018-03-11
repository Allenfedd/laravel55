import axios from 'axios'

axios.defaults.baseURL = '/api';

//Request interceptor
axios.interceptors.request.use(
    (config) => {

        //CSRF
        let csrf_token = document.head.querySelector('meta[name="csrf-token"]');
        if (csrf_token) {
            config.headers['X-CSRF-TOKEN'] = csrf_token.content;
        }

        //JWT
        let token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }

        config.headers['X-Requested-With'] = 'XMLHttpRequest';

        return config;
    },
    (error) => {
        return Promise.reject(error)
    }
);

//Response interceptor

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        return Promise.reject(error);
    }
);