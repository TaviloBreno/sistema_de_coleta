export default class WasteType {
  constructor({ id, name, unit, is_hazardous, description }) {
    this.id = id
    this.name = name
    this.unit = unit
    this.isHazardous = is_hazardous
    this.description = description
  }

  isHazardousText() {
    return this.isHazardous ? 'Sim' : 'Nao'
  }
}
