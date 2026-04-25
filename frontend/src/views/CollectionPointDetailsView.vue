<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionPointsApi from '@/api/CollectionPointsApi'
import collectionRoutesApi from '@/api/CollectionRoutesApi'
import collectionRecordsApi from '@/api/CollectionRecordsApi'
import wasteTypesApi from '@/api/WasteTypesApi'
import PointFormInputs from '@/components/PointFormInputs.vue'
import CollectionRecordFormInputs from '@/components/CollectionRecordFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const pointId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const isRecordSaving = ref(false)
const errorMessage = ref('')
const validationErrors = ref({})
const recordValidationErrors = ref({})
const routes = ref([])
const wasteTypes = ref([])
const records = ref([])

const form = ref({
  collection_route_id: 0,
  name: '',
  address: '',
  contact_name: '',
  contact_phone: '',
  scheduled_time: '',
  notes: '',
})

const recordForm = ref({
  collection_point_id: pointId,
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

function formatDate(value) {
  if (!value) {
    return '-'
  }

  const date = new Date(value)

  if (Number.isNaN(date.getTime())) {
    return value
  }

  return date.toLocaleString('pt-BR')
}

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar ponto de coleta.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchData() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [point, routesData, wasteTypesData, recordsData] = await Promise.all([
      collectionPointsApi.show(pointId),
      collectionRoutesApi.list(),
      wasteTypesApi.list(),
      collectionRecordsApi.list(pointId),
    ])

    routes.value = routesData
    wasteTypes.value = wasteTypesData
    records.value = recordsData

    form.value = {
      collection_route_id: point.collection_route_id || 0,
      name: point.name || '',
      address: point.address || '',
      contact_name: point.contact_name || '',
      contact_phone: point.contact_phone || '',
      scheduled_time: point.scheduled_time || '',
      notes: point.notes || '',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function updatePoint() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    await collectionPointsApi.update(pointId, form.value)
    toast.success('Ponto de coleta atualizado com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)
    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deletePoint() {
  if (!window.confirm('Deseja excluir este ponto de coleta?')) {
    return
  }

  isDeleting.value = true

  try {
    await collectionPointsApi.destroy(pointId)
    toast.success('Ponto excluido com sucesso.')
    router.push('/collection-routes')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

async function createRecord() {
  isRecordSaving.value = true
  recordValidationErrors.value = {}

  const payload = {
    ...recordForm.value,
    collection_point_id: pointId,
    collected_at: toApiDate(recordForm.value.collected_at),
  }

  try {
    const created = await collectionRecordsApi.create(payload)
    records.value = [created, ...records.value]
    recordForm.value = {
      collection_point_id: pointId,
      waste_type_id: 0,
      quantity: '',
      collected_at: '',
      notes: '',
    }
    toast.success('Registro criado com sucesso.')
  } catch (error) {
    recordValidationErrors.value = getValidationErrors(error)
    if (!Object.keys(recordValidationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isRecordSaving.value = false
  }
}

async function deleteRecord(item) {
  if (!window.confirm('Deseja excluir este registro de coleta?')) {
    return
  }

  try {
    await collectionRecordsApi.destroy(item.id)
    records.value = records.value.filter((record) => record.id !== item.id)
    toast.success('Registro excluido com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(fetchData)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes do ponto de coleta</h1>
        <p class="text-muted mb-0">Edite os dados e acompanhe os registros vinculados.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-routes">Voltar</RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Carregando ponto..." />

    <template v-else>
      <form class="card shadow-sm border-0" @submit.prevent="updatePoint">
        <div class="card-body">
          <PointFormInputs v-model="form" :errors="validationErrors" :routes="routes" />
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <button type="button" class="btn btn-outline-danger" :disabled="isDeleting" @click="deletePoint">Excluir ponto</button>
          <button type="submit" class="btn btn-success" :disabled="isSaving">Salvar alteracoes</button>
        </div>
      </form>

      <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Criar registro de coleta</h2>
        </div>
        <div class="card-body">
          <CollectionRecordFormInputs
            v-model="recordForm"
            :errors="recordValidationErrors"
            :waste-types="wasteTypes"
          />
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
          <button class="btn btn-success" :disabled="isRecordSaving" @click="createRecord">Criar registro</button>
        </div>
      </div>

      <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Registros deste ponto</h2>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th class="text-end">Acoes</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!records.length">
                <td colspan="5" class="text-center py-4 text-muted">Nenhum registro neste ponto.</td>
              </tr>
              <tr v-for="record in records" :key="record.id">
                <td>{{ record.id }}</td>
                <td>{{ wasteTypes.find((item) => item.id === record.waste_type_id)?.name || `#${record.waste_type_id}` }}</td>
                <td>{{ record.quantity }}</td>
                <td>{{ formatDate(record.collected_at) }}</td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <RouterLink :to="`/collection-records/${record.id}`" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i>
                    </RouterLink>
                    <button class="btn btn-outline-danger" @click="deleteRecord(record)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </section>
</template>
