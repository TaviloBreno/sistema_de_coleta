import BaseApi from '@/api/BaseApi'

class CollectionRecordsApi extends BaseApi {
  constructor() {
    super('collection-records')
  }

  async list(pointId = null) {
    if (pointId) {
      return this.getByPoint(pointId)
    }

    return this.get()
  }

  async getByPoint(pointId) {
    const response = await this.http.get(`collection-points/${pointId}/records`)
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

export default new CollectionRecordsApi()
