<script setup>
import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'

import wasteTypesApi from '@/api/WasteTypesApi'
import LoadSpinner from '@/components/LoadSpinner.vue'

const isLoading = ref(false)
const wasteTypes = ref([])
const errorMessage = ref('')

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar tipos de residuos.'
}

async function fetchWasteTypes() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    wasteTypes.value = await wasteTypesApi.list()
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function deleteWasteType(item) {
  if (!window.confirm(`Deseja excluir o tipo ${item.name}?`)) {
    return
  }

  try {
    await wasteTypesApi.destroy(item.id)
    wasteTypes.value = wasteTypes.value.filter((type) => type.id !== item.id)
    toast.success('Tipo de residuo excluido com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(fetchWasteTypes)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Tipos de residuos</h1>
        <p class="text-muted mb-0">Cadastre e mantenha os tipos de residuos disponiveis.</p>
      </div>
      <RouterLink class="btn btn-success" to="/waste-types/new">
        <i class="bi bi-plus-circle me-1"></i>
        Novo tipo
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Carregando tipos de residuos..." />

    <div v-else class="card shadow-sm border-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Tipo</th>
              <th>Unidade</th>
              <th>Perigoso</th>
              <th>Descricao</th>
              <th class="text-end">Acoes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!wasteTypes.length">
              <td colspan="5" class="text-center py-4 text-muted">Nenhum tipo de residuo cadastrado.</td>
            </tr>
            <tr v-for="item in wasteTypes" :key="item.id">
              <td>{{ item.name }}</td>
              <td>{{ item.unit }}</td>
              <td>{{ item.is_hazardous ? 'Sim' : 'Nao' }}</td>
              <td>{{ item.description || '-' }}</td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <RouterLink :to="`/waste-types/${item.id}`" class="btn btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </RouterLink>
                  <button class="btn btn-outline-danger" @click="deleteWasteType(item)">
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
