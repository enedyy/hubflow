import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost/tcc2/tcc_Make/hubflow/Backend/',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json'
    }
});

export default api;