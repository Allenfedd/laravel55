import axios from 'axios'
import store from '@/store'
import router from '@/router'

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
        const errorResponse = error.response;
        // refresh jwt token when token expired
        if (errorResponse.status === 401 && errorResponse.data.message == "Token has expired" && !errorResponse.config.isRetryRequest) {
            return new Promise((resolve, reject) => {
                axios.post('/api/refresh_token')
                    .then((response => {
                        errorResponse.config.isRetryRequest = true;
                        //set token
                        store.dispatch('setToken', response.data.token);

                        errorResponse.config.headers['Authorization'] = 'Bearer ' + response.data.token;

                        resolve(axios.request(errorResponse.config))
                    }))
                    .catch(error => {

                        store.dispatch('logOut');
                        router.push({name: 'login'});

                        reject(error)
                    })
            })
        }

        return Promise.reject(error);
    }
);