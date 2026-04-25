import BaseApi from '@/api/BaseApi'

class CollectionPointsApi extends BaseApi {
  constructor() {
    super('collection-points')
  }

  async list(routeId = null) {
    if (routeId) {
      return this.getByRoute(routeId)
    }

    return this.get()
  }

  async getByRoute(routeId) {
    const response = await this.http.get(`collection-routes/${routeId}/points`)
    return this.normalizePayload(response.data)
  }

  async show(id) {
    return this.get(id)
  }

  async create(payload) {
    return this.post(payload)
  }

  async update(id, payload) {
    return this.put(id, payload)
  }

  async destroy(id) {
    return this.delete(id)
  }
}

export default new CollectionPointsApi()
