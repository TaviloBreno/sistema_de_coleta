<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionPointsApi from '@/api/CollectionPointsApi'
import collectionRoutesApi from '@/api/CollectionRoutesApi'
import PointFormInputs from '@/components/PointFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const router = useRouter()
const route = useRoute()

const isLoading = ref(false)
const isSaving = ref(false)
const routes = ref([])
const validationErrors = ref({})
const form = ref({
  collection_route_id: 0,
  name: '',
  address: '',
  contact_name: '',
  contact_phone: '',
  scheduled_time: '',
  notes: '',
})

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao criar ponto de coleta.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchRoutes() {
  isLoading.value = true

  try {
    routes.value = await collectionRoutesApi.list()

    const routeIdQuery = Number(route.query.route_id)
    const hasRoute = routes.value.some((item) => item.id === routeIdQuery)

    if (hasRoute) {
      form.value.collection_route_id = routeIdQuery
    }
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isLoading.value = false
  }
}

async function createPoint() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    const point = await collectionPointsApi.create(form.value)
    toast.success('Ponto de coleta criado com sucesso.')
    router.push(`/collection-points/${point.id}`)
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

onMounted(fetchRoutes)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Novo ponto de coleta</h1>
        <p class="text-muted mb-0">Cadastre o ponto vinculado a uma rota de coleta.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-routes">Voltar</RouterLink>
    </div>

    <LoadSpinner v-if="isLoading" label="Carregando rotas..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="createPoint">
      <div class="card-body">
        <PointFormInputs v-model="form" :errors="validationErrors" :routes="routes" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-success" :disabled="isSaving">Criar ponto</button>
      </div>
    </form>
  </section>
</template>
