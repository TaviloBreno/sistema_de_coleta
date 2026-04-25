import { describe, it, expect } from 'vitest'
import WasteType from '@/Domain/Entities/WasteType'

describe('WasteType Entity', () => {
  it('creates an entity with correct properties', () => {
    const data = {
      id: 1,
      name: 'Plastico',
      unit: 'kg',
      is_hazardous: false,
      description: 'Garrafas PET'
    }

    const entity = new WasteType(data)

    expect(entity.id).toBe(1)
    expect(entity.name).toBe('Plastico')
    expect(entity.unit).toBe('kg')
    expect(entity.isHazardous).toBe(false)
    expect(entity.description).toBe('Garrafas PET')
  })

  it('returns correct hazardous text', () => {
    const hazardous = new WasteType({ is_hazardous: true })
    const nonHazardous = new WasteType({ is_hazardous: false })

    expect(hazardous.isHazardousText()).toBe('Sim')
    expect(nonHazardous.isHazardousText()).toBe('Nao')
  })
})
