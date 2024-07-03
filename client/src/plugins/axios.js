// src/plugins/axios.js
import axios from 'axios';
import store from '../store';

axios.defaults.baseURL = 'http://localhost:8000/api';

axios.interceptors.request.use(
    config => {
        const token = store.getters.token;
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

export default axios;
