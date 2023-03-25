import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: 'http://localhost:'+ process.env.BACKEND_PORT +'/api',
  headers: {'Content-Type': 'application/json'}
});

export default axiosInstance;
