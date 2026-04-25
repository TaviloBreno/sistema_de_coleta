<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionRecordsApi from '@/api/CollectionRecordsApi'
import collectionPointsApi from '@/api/CollectionPointsApi'
import wasteTypesApi from '@/api/WasteTypesApi'
import CollectionRecordFormInputs from '@/components/CollectionRecordFormInputs.vue'
import CollectionEventsPanel from '@/components/CollectionEventsPanel.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const recordId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const errorMessage = ref('')
const validationErrors = ref({})
const points = ref([])
const wasteTypes = ref([])
const form = ref({
  collection_point_id: 0,
  waste_type_id: 0,
  quantity: '',
  collected_at: '',
  notes: '',
})

function toApiDate(value) {
  if (!value) {
    return ''
  }

  return `${value.replace('T', ' ')}:00`
}

function toInputDate(value) {
  if (!value) {
    return ''
  }

  return value.replace(' ', 'T').slice(0, 16)
}

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar registro.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchData() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [recordData, pointsData, wasteTypesData] = await Promise.all([
      collectionRecordsApi.show(recordId),
      collectionPointsApi.list(),
      wasteTypesApi.list(),
    ])

    points.value = pointsData
    wasteTypes.value = wasteTypesData

    form.value = {
      collection_point_id: recordData.collection_point_id || 0,
      waste_type_id: recordData.waste_type_id || 0,
      quantity: recordData.quantity || '',
      collected_at: toInputDate(recordData.collected_at),
      notes: recordData.notes || '',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function updateRecord() {
  isSaving.value = true
  validationErrors.value = {}

  const payload = {
    ...form.value,
    collected_at: toApiDate(form.value.collected_at),
  }

  try {
    await collectionRecordsApi.update(recordId, payload)
    toast.success('Registro atualizado com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteRecord() {
  if (!window.confirm('Deseja excluir este registro?')) {
    return
  }

  isDeleting.value = true

  try {
    await collectionRecordsApi.destroy(recordId)
    toast.success('Registro excluido com sucesso.')
    router.push('/collection-routes')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

onMounted(fetchData)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes do registro de coleta</h1>
        <p class="text-muted mb-0">Edite o registro e acompanhe os eventos da coleta.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-routes">Voltar</RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <LoadSpinner v-if="isLoading" label="Carregando registro..." />

    <template v-else>
      <form class="card shadow-sm border-0" @submit.prevent="updateRecord">
        <div class="card-body">
          <CollectionRecordFormInputs v-model="form" :errors="validationErrors" :waste-types="wasteTypes" />

          <div class="mt-3">
            <label class="form-label">Ponto de coleta</label>
            <select
              class="form-select"
              :class="{ 'is-invalid': validationErrors.collection_point_id }"
              v-model.number="form.collection_point_id"
            >
              <option :value="0">Selecione...</option>
              <option v-for="point in points" :key="point.id" :value="point.id">{{ point.name }}</option>
            </select>
            <div v-if="validationErrors.collection_point_id" class="invalid-feedback d-block">{{ validationErrors.collection_point_id }}</div>
          </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between">
          <button type="button" class="btn btn-outline-danger" :disabled="isDeleting" @click="deleteRecord">Excluir</button>
          <button type="submit" class="btn btn-success" :disabled="isSaving">Salvar alteracoes</button>
        </div>
      </form>

      <CollectionEventsPanel :record-id="recordId" />
    </template>
  </section>
</template>
