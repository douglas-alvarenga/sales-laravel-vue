import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8001/api',
})

api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token')
  const baseURL = 'http://localhost:8001/api'
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export default api
