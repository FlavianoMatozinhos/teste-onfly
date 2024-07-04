import Vue from 'vue';
import Vuex from 'vuex';
import axios from "axios";

Vue.use(Vuex);

axios.defaults.baseURL = 'http://localhost:8000/api';

export default new Vuex.Store({
    state: {
        token: localStorage.getItem('token') || null
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
        token(state) {
            return state.token;
        }
    },
    mutations: {
        setToken(state, token) {
            state.token = token;
        },
        removeToken(state) {
            state.token = null;
        }
    },
    actions: {
        async login({ commit }, form) {
            try {
                const response = await axios.post('/login', {
                    email: form.email,
                    password: form.password
                });
                const token = response.data.data.token;
                commit('setToken', token);
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                return response;
            } catch (error) {
                throw new Error('Erro durante o login: ' + error.message);
            }
        },
        logout({ commit }) {
            commit('removeToken');
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
        }
    }
});
