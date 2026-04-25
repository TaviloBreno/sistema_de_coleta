<script setup>
import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'

import collectionRoutesApi from '@/api/CollectionRoutesApi'
import companiesApi from '@/api/CompaniesApi'
import CollectionRoutesList from '@/components/CollectionRoutesList.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const routes = ref([])
const companiesMap = ref({})
const isLoading = ref(false)
const errorMessage = ref('')

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar rotas.'
}

async function fetchData() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [routesData, companies] = await Promise.all([collectionRoutesApi.list(), companiesApi.list()])

    companiesMap.value = companies.reduce((acc, company) => {
      acc[company.id] = company.name
      return acc
    }, {})

    routes.value = routesData.map((item) => ({
      ...item,
      company_name: companiesMap.value[item.company_id] || null,
    }))
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function removeRoute(routeItem) {
  if (!window.confirm(`Deseja excluir a rota ${routeItem.name}?`)) {
    return
  }

  try {
    await collectionRoutesApi.destroy(routeItem.id)
    routes.value = routes.value.filter((item) => item.id !== routeItem.id)
    toast.success('Rota excluida com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(fetchData)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Rotas de Coleta</h1>
        <p class="text-muted mb-0">Gerencie as rotas de coleta cadastradas no sistema.</p>
      </div>
      <RouterLink to="/collection-routes/new" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>
        Nova Rota
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Buscando rotas..." />

    <CollectionRoutesList
      v-else
      :routes="routes"
      title="Lista de Rotas"
      empty-message="Nenhuma rota de coleta cadastrada."
      @delete="removeRoute"
    />
  </section>
</template>
