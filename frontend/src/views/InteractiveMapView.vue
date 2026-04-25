<script setup>
import { onMounted, ref } from 'vue'

import collectionRoutesApi from '@/api/CollectionRoutesApi'
import collectionPointsApi from '@/api/CollectionPointsApi'
import CollectionRouteMap from '@/components/CollectionRouteMap.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const routes = ref([])
const selectedRoute = ref(null)
const selectedRoutePoints = ref([])
const loadingRoutes = ref(false)
const loadingPoints = ref(false)
const errorMessage = ref('')

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar dados do mapa.'
}

async function loadRoutes() {
  loadingRoutes.value = true
  errorMessage.value = ''

  try {
    routes.value = await collectionRoutesApi.list()

    if (routes.value.length) {
      await selectRoute(routes.value[0])
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    loadingRoutes.value = false
  }
}

async function selectRoute(routeItem) {
  selectedRoute.value = routeItem
  loadingPoints.value = true

  try {
    selectedRoutePoints.value = await collectionPointsApi.list(routeItem.id)
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    loadingPoints.value = false
  }
}

onMounted(loadRoutes)
</script>

<template>
  <section class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Mapa Interativo de Coleta</h1>
        <p class="text-muted mb-0">Acompanhe a passagem das rotas em Crateus.</p>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="loadingRoutes" label="Carregando rotas no mapa..." />

    <div v-else class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white">
            <h2 class="h5 mb-0">Rotas disponiveis</h2>
          </div>
          <div class="list-group list-group-flush">
            <button
              v-for="item in routes"
              :key="item.id"
              type="button"
              class="list-group-item list-group-item-action"
              :class="{ active: selectedRoute?.id === item.id }"
              @click="selectRoute(item)"
            >
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-semibold">{{ item.name }}</div>
                  <small class="opacity-75">{{ item.start_point }} -> {{ item.end_point }}</small>
                </div>
                <span class="badge text-bg-light text-dark">#{{ item.id }}</span>
              </div>
            </button>
            <div v-if="!routes.length" class="list-group-item text-muted">Nenhuma rota cadastrada.</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-8">
        <LoadSpinner v-if="loadingPoints" label="Carregando pontos da rota..." />
        <CollectionRouteMap
          v-else-if="selectedRoute"
          :route-name="selectedRoute.name"
          :points="selectedRoutePoints"
        />
        <div v-else class="alert alert-info mb-0">Selecione uma rota para visualizar no mapa.</div>
      </div>
    </div>
  </section>
</template>
