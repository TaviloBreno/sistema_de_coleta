import BaseApi from '@/api/BaseApi'

class CollectionEventsApi extends BaseApi {
  constructor() {
    super('collection-events')
  }

  async list(recordId = null) {
    if (recordId) {
      return this.getByRecord(recordId)
    }

    return this.get()
  }

  async getByRecord(recordId) {
    const response = await this.http.get(`collection-records/${recordId}/events`)
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

export default new CollectionEventsApi()
