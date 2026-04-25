<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import wasteTypesApi from '@/api/WasteTypesApi'
import WasteTypeFormInputs from '@/components/WasteTypeFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const wasteTypeId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const errorMessage = ref('')
const validationErrors = ref({})
const form = ref({
  name: '',
  unit: 'kg',
  is_hazardous: 0,
  description: '',
})

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar tipo de residuo.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchWasteType() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const data = await wasteTypesApi.show(wasteTypeId)
    form.value = {
      name: data.name || '',
      unit: data.unit || 'kg',
      is_hazardous: Number(data.is_hazardous) || 0,
      description: data.description || '',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function updateWasteType() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    await wasteTypesApi.update(wasteTypeId, form.value)
    toast.success('Tipo de residuo atualizado com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteWasteType() {
  if (!window.confirm('Deseja excluir este tipo de residuo?')) {
    return
  }

  isDeleting.value = true

  try {
    await wasteTypesApi.destroy(wasteTypeId)
    toast.success('Tipo de residuo excluido com sucesso.')
    router.push('/waste-types')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

onMounted(fetchWasteType)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes do tipo de residuo</h1>
        <p class="text-muted mb-0">Edite ou exclua o tipo selecionado.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/waste-types">Voltar</RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <LoadSpinner v-if="isLoading" label="Carregando tipo de residuo..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="updateWasteType">
      <div class="card-body">
        <WasteTypeFormInputs v-model="form" :errors="validationErrors" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-between">
        <button type="button" class="btn btn-outline-danger" :disabled="isDeleting" @click="deleteWasteType">Excluir</button>
        <button type="submit" class="btn btn-success" :disabled="isSaving">Salvar alteracoes</button>
      </div>
    </form>
  </section>
</template>
