import BaseApi from '@/api/BaseApi'

class CollectionRoutesApi extends BaseApi {
  constructor() {
    super('collection-routes')
  }

  async list(companyId = null) {
    if (companyId) {
      return this.getByCompany(companyId)
    }

    return this.get()
  }

  async getByCompany(companyId) {
    const response = await this.http.get(`companies/${companyId}/collection-routes`)
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

export default new CollectionRoutesApi()
