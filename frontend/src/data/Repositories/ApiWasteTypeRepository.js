import WasteTypeRepositoryInterface from '@/Domain/Repositories/WasteTypeRepositoryInterface'
import wasteTypesApi from '@/api/WasteTypesApi'
import WasteType from '@/Domain/Entities/WasteType'

export default class ApiWasteTypeRepository extends WasteTypeRepositoryInterface {
  async getAll() {
    const data = await wasteTypesApi.list()
    return data.map(item => new WasteType(item))
  }

  async delete(id) {
    return await wasteTypesApi.destroy(id)
  }
}
