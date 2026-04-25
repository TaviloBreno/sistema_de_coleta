import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'
import LoadSpinner from '@/components/LoadSpinner.vue'

import ApiWasteTypeRepository from '@/Data/Repositories/ApiWasteTypeRepository'
import GetWasteTypesUseCase from '@/Domain/UseCases/GetWasteTypesUseCase'
import DeleteWasteTypeUseCase from '@/Domain/UseCases/DeleteWasteTypeUseCase'

export default {
  components: {
    LoadSpinner
  },
  setup() {
    const isLoading = ref(false)
    const wasteTypes = ref([])
    const errorMessage = ref('')

    const repository = new ApiWasteTypeRepository()
    const getUseCase = new GetWasteTypesUseCase(repository)
    const deleteUseCase = new DeleteWasteTypeUseCase(repository)

    function getErrorMessage(error) {
      return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar tipos de residuos.'
    }

    async function fetchWasteTypes() {
      isLoading.value = true
      errorMessage.value = ''

      try {
        wasteTypes.value = await getUseCase.execute()
      } catch (error) {
        errorMessage.value = getErrorMessage(error)
      } finally {
        isLoading.value = false
      }
    }

    async function deleteWasteType(item) {
      if (!window.confirm(`Deseja excluir o tipo ${item.name}?`)) {
        return
      }

      try {
        await deleteUseCase.execute(item.id)
        wasteTypes.value = wasteTypes.value.filter((type) => type.id !== item.id)
        toast.success('Tipo de residuo excluido com sucesso.')
      } catch (error) {
        toast.error(getErrorMessage(error))
      }
    }

    onMounted(fetchWasteTypes)

    return {
      isLoading,
      wasteTypes,
      errorMessage,
      deleteWasteType
    }
  }
}
