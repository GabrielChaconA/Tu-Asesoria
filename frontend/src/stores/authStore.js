import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import router from '@/router'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const isAuthenticated = ref(false)
  const token = ref(localStorage.getItem('token') || null)

  // Getters de Roles y Estados
  const isStudent = computed(() => user.value?.role === 'USER' && !user.value?.is_tutor)
  const isTutor = computed(() => user.value?.role === 'USER' && user.value?.is_tutor)
  const isAdmin = computed(() => user.value?.role === 'ADMIN')
  const isApprovedTutor = computed(() => isTutor.value && user.value?.verification_status === 'APPROVED')

  const login = async (credentials) => {
    try {
      const response = await api.post('/login', credentials)
      
      setAuth(response.data.user, response.data.token)
      return true
    } catch (error) {
      console.error('Login error', error)
      return false
    }
  }

  const register = async (userData) => {
    try {
      const response = await api.post('/register', userData)
      
      setAuth(response.data.user, response.data.token)
      return true
    } catch (error) {
      console.error('Register error', error)
      return false
    }
  }

  const logout = () => {
    user.value = null
    isAuthenticated.value = false
    token.value = null
    localStorage.removeItem('token')
    router.push({ name: 'auth' })
  }

  const fetchUser = async () => {
    if (!token.value) return false
    try {
      const response = await api.get('/me')
      user.value = response.data
      isAuthenticated.value = true
      return true
    } catch (error) {
      console.error('Error fetching user', error)
      logout()
      return false
    }
  }

  const setAuth = (userData, authToken) => {
    user.value = userData
    isAuthenticated.value = true
    token.value = authToken
    localStorage.setItem('token', authToken)
  }

  return {
    user,
    isAuthenticated,
    token,
    isStudent,
    isTutor,
    isAdmin,
    isApprovedTutor,
    isApprovedTutor,
    login,
    register,
    logout,
    fetchUser,
    setAuth
  }
})
