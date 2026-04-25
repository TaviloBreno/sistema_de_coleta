<script setup>
import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'

import collectionRequestsApi from '@/api/CollectionRequestsApi'
import LoadSpinner from '@/components/LoadSpinner.vue'

const requests = ref([])
const isLoading = ref(false)
const errorMessage = ref('')

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
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar solicitacoes.'
}

async function fetchRequests() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    requests.value = await collectionRequestsApi.list()
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function deleteRequest(item) {
  if (!window.confirm(`Deseja excluir a solicitacao ${item.title}?`)) {
    return
  }

  try {
    await collectionRequestsApi.destroy(item.id)
    requests.value = requests.value.filter((request) => request.id !== item.id)
    toast.success('Solicitacao excluida com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(fetchRequests)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Solicitacoes de coleta</h1>
        <p class="text-muted mb-0">Acompanhe as solicitacoes do usuario logado.</p>
      </div>
      <RouterLink class="btn btn-success" to="/collection-requests/new">
        <i class="bi bi-plus-circle me-1"></i>
        Nova solicitacao
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <LoadSpinner v-if="isLoading" label="Carregando solicitacoes..." />

    <div v-else class="card shadow-sm border-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Titulo</th>
              <th>Rota</th>
              <th>Ponto</th>
              <th>Status</th>
              <th>Programada</th>
              <th class="text-end">Acoes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!requests.length">
              <td colspan="6" class="text-center py-4 text-muted">Nenhuma solicitacao encontrada.</td>
            </tr>
            <tr v-for="item in requests" :key="item.id">
              <td>{{ item.title }}</td>
              <td>{{ item.route_name || `#${item.collection_route_id}` }}</td>
              <td>{{ item.point_name || `#${item.collection_point_id}` }}</td>
              <td>{{ item.status }}</td>
              <td>{{ formatDate(item.scheduled_at) }}</td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <RouterLink :to="`/collection-requests/${item.id}`" class="btn btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </RouterLink>
                  <button class="btn btn-outline-danger" @click="deleteRequest(item)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>
