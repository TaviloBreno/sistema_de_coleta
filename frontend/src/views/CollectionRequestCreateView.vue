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

const isLoading = ref(false)
const isSaving = ref(false)
const routes = ref([])
const points = ref([])
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

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao criar solicitacao.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchRoutes() {
  isLoading.value = true

  try {
    routes.value = await collectionRoutesApi.list()

    const routeId = Number(route.query.route_id)
    if (routeId) {
      form.value.collection_route_id = routeId
    }
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isLoading.value = false
  }
}

async function fetchPoints() {
  if (!form.value.collection_route_id) {
    points.value = []
    form.value.collection_point_id = 0
    return
  }

  points.value = await collectionPointsApi.list(form.value.collection_route_id)

  if (!points.value.some((point) => point.id === form.value.collection_point_id)) {
    form.value.collection_point_id = 0
  }
}

watch(
  () => form.value.collection_route_id,
  async () => {
    await fetchPoints()
  }
)

async function createRequest() {
  isSaving.value = true
  validationErrors.value = {}

  const payload = {
    ...form.value,
    scheduled_at: toApiDate(form.value.scheduled_at),
  }

  try {
    const created = await collectionRequestsApi.create(payload)
    toast.success('Solicitacao criada com sucesso.')
    router.push(`/collection-requests/${created.id}`)
  } catch (error) {
    validationErrors.value = getValidationErrors(error)
    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

onMounted(async () => {
  await fetchRoutes()
  await fetchPoints()
})
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Nova solicitacao de coleta</h1>
        <p class="text-muted mb-0">Crie uma nova solicitacao para o usuario logado.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-requests">Voltar</RouterLink>
    </div>

    <LoadSpinner v-if="isLoading" label="Carregando dados iniciais..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="createRequest">
      <div class="card-body">
        <CollectionRequestFormInputs v-model="form" :errors="validationErrors" :routes="routes" :points="points" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-success" :disabled="isSaving">Criar solicitacao</button>
      </div>
    </form>
  </section>
</template>
