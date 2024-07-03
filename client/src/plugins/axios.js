import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000/api'; // Altere para a URL correta do seu backend Laravel

export default axios;