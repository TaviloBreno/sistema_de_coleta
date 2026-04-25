<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionPointsApi from '@/api/CollectionPointsApi'
import collectionRequestsApi from '@/api/CollectionRequestsApi'
import collectionRoutesApi from '@/api/CollectionRoutesApi'
import CollectionRequestFormInputs from '@/components/CollectionRequestFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const requestId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const routes = ref([])
const points = ref([])
const errorMessage = ref('')
const validationErrors = ref({})
const form = ref({
  collection_route_id: 0,
  collection_point_id: 0,
  title: '',
  description: '',
  scheduled_at: '',
  status: 'pendente',
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
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar solicitacao.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchRoutesAndRequest() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [requestData, routesData] = await Promise.all([
      collectionRequestsApi.show(requestId),
      collectionRoutesApi.list(),
    ])

    routes.value = routesData
    form.value = {
      collection_route_id: requestData.collection_route_id || 0,
      collection_point_id: requestData.collection_point_id || 0,
      title: requestData.title || '',
      description: requestData.description || '',
      scheduled_at: toInputDate(requestData.scheduled_at),
      status: requestData.status || 'pendente',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function fetchPoints(routeId) {
  if (!routeId) {
    points.value = []
    return
  }

  points.value = await collectionPointsApi.list(routeId)
}

watch(
  () => form.value.collection_route_id,
  async (routeId) => {
    await fetchPoints(routeId)
  }
)

async function updateRequest() {
  isSaving.value = true
  validationErrors.value = {}

  const payload = {
    ...form.value,
    scheduled_at: toApiDate(form.value.scheduled_at),
  }

  try {
    await collectionRequestsApi.update(requestId, payload)
    toast.success('Solicitacao atualizada com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)
    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteRequest() {
  if (!window.confirm('Deseja excluir esta solicitacao?')) {
    return
  }

  isDeleting.value = true

  try {
    await collectionRequestsApi.destroy(requestId)
    toast.success('Solicitacao excluida com sucesso.')
    router.push('/collection-requests')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

onMounted(async () => {
  await fetchRoutesAndRequest()
  await fetchPoints(form.value.collection_route_id)
})
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes da solicitacao</h1>
        <p class="text-muted mb-0">Edite a solicitacao e acompanhe os eventos da coleta.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-requests">Voltar</RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <LoadSpinner v-if="isLoading" label="Carregando solicitacao..." />

    <template v-else>
      <form class="card shadow-sm border-0" @submit.prevent="updateRequest">
        <div class="card-body">
          <CollectionRequestFormInputs v-model="form" :errors="validationErrors" :routes="routes" :points="points" />
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <button type="button" class="btn btn-outline-danger" :disabled="isDeleting" @click="deleteRequest">Excluir</button>
          <button type="submit" class="btn btn-success" :disabled="isSaving">Salvar alteracoes</button>
        </div>
      </form>

    </template>
  </section>
</template>
