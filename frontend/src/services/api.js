import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'

// API base URL configuration (can be driven by env vars)
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

// Optional: Interceptors for auth tokens, etc.
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && !error.config.url.includes('/login')) {
      const authStore = useAuthStore()
      authStore.logout()
      alert('Tu sesión ha expirado o es inválida. Por favor, inicia sesión nuevamente.')
    }
    return Promise.reject(error)
  }
)

export default api
