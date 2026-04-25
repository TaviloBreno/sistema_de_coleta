<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionRoutesApi from '@/api/CollectionRoutesApi'
import collectionPointsApi from '@/api/CollectionPointsApi'
import companiesApi from '@/api/CompaniesApi'
import CollectionRouteFormInputs from '@/components/CollectionRouteFormInputs.vue'
import CollectionPointsList from '@/components/CollectionPointsList.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const routeId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const isPointsLoading = ref(false)
const errorMessage = ref('')
const pointsErrorMessage = ref('')
const validationErrors = ref({})
const companies = ref([])
const points = ref([])
const form = ref({
  company_id: 0,
  name: '',
  start_point: '',
  end_point: '',
  scheduled_at: '',
  status: 'pendente',
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
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar requisicao da rota.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchData() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [routeData, companiesData] = await Promise.all([
      collectionRoutesApi.show(routeId),
      companiesApi.list(),
    ])

    companies.value = companiesData

    form.value = {
      company_id: routeData.company_id || 0,
      name: routeData.name || '',
      start_point: routeData.start_point || '',
      end_point: routeData.end_point || '',
      scheduled_at: toInputDate(routeData.scheduled_at),
      status: routeData.status || 'pendente',
      notes: routeData.notes || '',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function fetchPoints() {
  isPointsLoading.value = true
  pointsErrorMessage.value = ''

  try {
    points.value = await collectionPointsApi.list(routeId)
  } catch (error) {
    pointsErrorMessage.value = getErrorMessage(error)
  } finally {
    isPointsLoading.value = false
  }
}

async function updateRoute() {
  isSaving.value = true
  validationErrors.value = {}

  const payload = {
    ...form.value,
    scheduled_at: toApiDate(form.value.scheduled_at),
  }

  try {
    await collectionRoutesApi.update(routeId, payload)
    toast.success('Rota atualizada com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteRoute() {
  if (!window.confirm('Deseja excluir esta rota de coleta?')) {
    return
  }

  isDeleting.value = true

  try {
    await collectionRoutesApi.destroy(routeId)
    toast.success('Rota excluida com sucesso.')
    router.push('/collection-routes')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

async function deletePoint(item) {
  if (!window.confirm(`Deseja excluir o ponto ${item.name}?`)) {
    return
  }

  try {
    await collectionPointsApi.destroy(item.id)
    points.value = points.value.filter((point) => point.id !== item.id)
    toast.success('Ponto de coleta excluido com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(async () => {
  await Promise.all([fetchData(), fetchPoints()])
})
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes da rota</h1>
        <p class="text-muted mb-0">Visualize e edite os dados da rota de coleta.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-routes">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Carregando dados da rota..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="updateRoute">
      <div class="card-body">
        <CollectionRouteFormInputs v-model="form" :errors="validationErrors" :companies="companies" />
      </div>

      <div class="card-footer bg-white d-flex justify-content-between">
        <button
          type="button"
          class="btn btn-outline-danger"
          :disabled="isDeleting"
          @click="deleteRoute"
        >
          <span v-if="isDeleting" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          Excluir
        </button>

        <button type="submit" class="btn btn-success" :disabled="isSaving">
          <span v-if="isSaving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          Salvar alteracoes
        </button>
      </div>
    </form>

    <div class="d-flex justify-content-end mt-3">
      <RouterLink class="btn btn-success" :to="`/collection-points/new?route_id=${routeId}`">
        <i class="bi bi-plus-circle me-1"></i>
        Novo ponto de coleta
      </RouterLink>
    </div>

    <div class="mt-3">
      <div v-if="pointsErrorMessage" class="alert alert-danger mb-3">{{ pointsErrorMessage }}</div>
      <LoadSpinner v-if="isPointsLoading" label="Carregando pontos da rota..." />
      <CollectionPointsList v-else :points="points" @delete="deletePoint" />
    </div>
  </section>
</template>
