import BaseApi from '@/api/BaseApi'

class CompaniesApi extends BaseApi {
  constructor() {
    super('companies')
  }

  async list() {
    return this.get()
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

export default new CompaniesApi()
