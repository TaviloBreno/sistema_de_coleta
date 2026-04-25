<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import wasteTypesApi from '@/api/WasteTypesApi'
import WasteTypeFormInputs from '@/components/WasteTypeFormInputs.vue'

const router = useRouter()

const isSaving = ref(false)
const validationErrors = ref({})
const form = ref({
  name: '',
  unit: 'kg',
  is_hazardous: 0,
  description: '',
})

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao criar tipo de residuo.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function createWasteType() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    const created = await wasteTypesApi.create(form.value)
    toast.success('Tipo de residuo criado com sucesso.')
    router.push(`/waste-types/${created.id}`)
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Novo tipo de residuo</h1>
        <p class="text-muted mb-0">Cadastre o tipo para uso nos registros de coleta.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/waste-types">Voltar</RouterLink>
    </div>

    <form class="card shadow-sm border-0" @submit.prevent="createWasteType">
      <div class="card-body">
        <WasteTypeFormInputs v-model="form" :errors="validationErrors" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-success" :disabled="isSaving">Criar tipo</button>
      </div>
    </form>
  </section>
</template>
