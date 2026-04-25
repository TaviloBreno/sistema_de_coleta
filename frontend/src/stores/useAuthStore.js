import { defineStore } from 'pinia'

import AuthApi from '@/api/AuthApi'
import { clearStoredToken, getStoredToken, storeToken } from '@/api/httpClient'

const USER_KEY = 'sistema_coleta_user'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: getStoredToken() || '',
    user: JSON.parse(localStorage.getItem(USER_KEY) || 'null'),
    initialized: false,
    loading: false,
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
    role: (state) => state.user?.role || null,
    canManageCatalog: (state) => ['admin', 'manager'].includes(state.user?.role),
    canViewAdminLinks: (state) => ['admin', 'manager'].includes(state.user?.role),
  },
  actions: {
    persistSession(token, user) {
      this.token = token
      this.user = user
      storeToken(token)
      localStorage.setItem(USER_KEY, JSON.stringify(user))
    },
    clearSession() {
      this.token = ''
      this.user = null
      clearStoredToken()
      localStorage.removeItem(USER_KEY)
    },
    async initialize() {
      if (this.initialized) {
        return
      }

      this.initialized = true

      if (!this.token) {
        return
      }

      try {
        this.user = await AuthApi.me()
        localStorage.setItem(USER_KEY, JSON.stringify(this.user))
      } catch {
        this.clearSession()
      }
    },
    async login(payload) {
      this.loading = true

      try {
        const response = await AuthApi.login(payload)
        this.persistSession(response.token, response.user)
        return response
      } finally {
        this.loading = false
      }
    },
    async register(payload) {
      this.loading = true

      try {
        const response = await AuthApi.register(payload)
        this.persistSession(response.token, response.user)
        return response
      } finally {
        this.loading = false
      }
    },
    async logout() {
      try {
        await AuthApi.logout()
      } finally {
        this.clearSession()
      }
    },
  },
})
