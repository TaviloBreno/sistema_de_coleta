export default class GetWasteTypesUseCase {
  constructor(repository) {
    this.repository = repository
  }

  async execute() {
    return await this.repository.getAll()
  }
}
