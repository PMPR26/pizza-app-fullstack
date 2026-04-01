import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { api } from '@/services/api'
import type { User } from '@/types'

interface AuthPayload {
  data: User
  token: string
}

interface MePayload {
  data: User
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const loading = ref(false)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  function setSession(payload: AuthPayload) {
    user.value = payload.data
    token.value = payload.token
    localStorage.setItem('token', payload.token)
  }

  async function login(email: string, password: string) {
    loading.value = true
    try {
      const { data } = await api.post<AuthPayload>('/login', { email, password })
      setSession(data)
    } finally {
      loading.value = false
    }
  }

  async function register(name: string, email: string, password: string) {
    loading.value = true
    try {
      const { data } = await api.post<AuthPayload>('/register', { name, email, password })
      setSession(data)
    } finally {
      loading.value = false
    }
  }

  async function fetchMe() {
    if (!token.value) return
    try {
      const { data } = await api.get<MePayload>('/me')
      user.value = data.data
    } catch {
      logoutLocal()
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } finally {
      logoutLocal()
    }
  }

  function logoutLocal() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
  }

  return {
    user,
    token,
    loading,
    isAuthenticated,
    isAdmin,
    login,
    register,
    fetchMe,
    logout,
    logoutLocal,
  }
})
