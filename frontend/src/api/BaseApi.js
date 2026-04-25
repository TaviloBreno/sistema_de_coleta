import { createApiClient } from '@/api/httpClient'

export default class BaseApi {
  constructor(resource) {
    this.resource = resource
    this.http = createApiClient()
  }

  buildPath(id = '') {
    return id ? `${this.resource}/${id}` : this.resource
  }

  normalizePayload(payload) {
    if (
      payload &&
      typeof payload === 'object' &&
      !Array.isArray(payload) &&
      Object.prototype.hasOwnProperty.call(payload, 'data')
    ) {
      return payload.data
    }

    return payload
  }

  async get(id = '') {
    const response = await this.http.get(this.buildPath(id))
    return this.normalizePayload(response.data)
  }

  async post(payload) {
    const response = await this.http.post(this.buildPath(), payload)
    return this.normalizePayload(response.data)
  }

  async put(id, payload) {
    const response = await this.http.put(this.buildPath(id), payload)
    return this.normalizePayload(response.data)
  }

  async delete(id) {
    const response = await this.http.delete(this.buildPath(id))
    return this.normalizePayload(response.data)
  }
}
