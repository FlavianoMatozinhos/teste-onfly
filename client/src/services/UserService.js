import axios from '../plugins/axios';

const API_URL = '/users';

export default {
    getAllUsers() {
        return axios.get(API_URL);
    },

    getUser(id) {
        return axios.get(`${API_URL}/${id}`);
    },

    createUser(userData) {
        return axios.post(API_URL, userData);
    },

    updateUser(id, userData) {
        return axios.put(`${API_URL}/${id}`, userData);
    },

    deleteUser(id) {
        return axios.delete(`${API_URL}/${id}`);
    }
};
