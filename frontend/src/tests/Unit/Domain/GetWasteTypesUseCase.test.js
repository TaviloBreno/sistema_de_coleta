import { describe, it, expect, vi } from 'vitest'
import GetWasteTypesUseCase from '@/Domain/UseCases/GetWasteTypesUseCase'
import WasteTypeRepositoryInterface from '@/Domain/Repositories/WasteTypeRepositoryInterface'

describe('GetWasteTypesUseCase', () => {
  it('calls repository getAll and returns the data', async () => {
    const mockData = [{ id: 1, name: 'Papel' }]
    
    // Create a mock repository
    const mockRepository = new WasteTypeRepositoryInterface()
    mockRepository.getAll = vi.fn().mockResolvedValue(mockData)

    const useCase = new GetWasteTypesUseCase(mockRepository)
    
    const result = await useCase.execute()

    expect(mockRepository.getAll).toHaveBeenCalledOnce()
    expect(result).toEqual(mockData)
  })
})
