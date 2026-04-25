<script setup>
import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'

import companiesApi from '@/api/CompaniesApi'
import LoadSpinner from '@/components/LoadSpinner.vue'

const companies = ref([])
const isLoading = ref(false)
const deletingId = ref(null)
const errorMessage = ref('')

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar empresas.'
}

async function fetchCompanies() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    companies.value = await companiesApi.list()
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function removeCompany(company) {
  if (!window.confirm(`Deseja excluir a empresa ${company.name}?`)) {
    return
  }

  deletingId.value = company.id

  try {
    await companiesApi.destroy(company.id)
    companies.value = companies.value.filter((item) => item.id !== company.id)
    toast.success('Empresa excluida com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    deletingId.value = null
  }
}

onMounted(fetchCompanies)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Empresas</h1>
        <p class="text-muted mb-0">Gerencie as empresas cadastradas no sistema.</p>
      </div>
      <RouterLink to="/companies/new" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>
        Nova Empresa
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Buscando empresas..." />

    <div v-else class="card shadow-sm border-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Telefone</th>
              <th>E-mail</th>
              <th>Endereco</th>
              <th class="text-end">Acoes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!companies.length">
              <td colspan="6" class="text-center py-4 text-muted">
                Nenhuma empresa cadastrada.
              </td>
            </tr>
            <tr v-for="company in companies" :key="company.id">
              <td>{{ company.id }}</td>
              <td>{{ company.name }}</td>
              <td>{{ company.phone }}</td>
              <td>{{ company.email }}</td>
              <td>{{ company.address }}</td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <RouterLink :to="`/companies/${company.id}`" class="btn btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </RouterLink>
                  <button
                    class="btn btn-outline-danger"
                    :disabled="deletingId === company.id"
                    @click="removeCompany(company)"
                  >
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
