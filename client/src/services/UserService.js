import axios from '../plugins/axios';

const API_URL = '/users'; // Rota base para usuários no Laravel

export default {
    // Busca todos os usuários
    getAllUsers() {
        return axios.get(API_URL);
    },

    // Busca um usuário específico por ID
    getUser(id) {
        return axios.get(`${API_URL}/${id}`);
    },

    // Cria um novo usuário
    createUser(userData) {
        return axios.post(API_URL, userData);
    },

    // Atualiza um usuário existente por ID
    updateUser(id, userData) {
        return axios.put(`${API_URL}/${id}`, userData);
    },

    // Exclui um usuário por ID
    deleteUser(id) {
        return axios.delete(`${API_URL}/${id}`);
    }
};
