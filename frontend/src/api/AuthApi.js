import { createApiClient } from '@/api/httpClient'

const http = createApiClient()

export default {
  async register(payload) {
    const response = await http.post('/register', payload)
    return response.data
  },

  async login(payload) {
    const response = await http.post('/login', payload)
    return response.data
  },

  async me() {
    const response = await http.get('/user')
    return response.data
  },

  async logout() {
    const response = await http.post('/logout')
    return response.data
  },
}
