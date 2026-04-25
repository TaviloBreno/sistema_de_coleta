<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import companiesApi from '@/api/CompaniesApi'
import CompanyFormInputs from '@/components/CompanyFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const route = useRoute()
const router = useRouter()

const companyId = Number(route.params.id)

const isLoading = ref(false)
const isSaving = ref(false)
const isDeleting = ref(false)
const errorMessage = ref('')
const validationErrors = ref({})
const form = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
})

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Ocorreu um erro ao processar a requisicao.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchCompany() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const company = await companiesApi.show(companyId)
    form.value = {
      name: company.name || '',
      phone: company.phone || '',
      email: company.email || '',
      address: company.address || '',
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

async function updateCompany() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    await companiesApi.update(companyId, form.value)
    toast.success('Empresa atualizada com sucesso.')
  } catch (error) {
    validationErrors.value = getValidationErrors(error)
    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteCompany() {
  if (!window.confirm('Deseja excluir esta empresa?')) {
    return
  }

  isDeleting.value = true

  try {
    await companiesApi.destroy(companyId)
    toast.success('Empresa excluida com sucesso.')
    router.push('/companies')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isDeleting.value = false
  }
}

onMounted(fetchCompany)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Detalhes da empresa</h1>
        <p class="text-muted mb-0">Visualize e edite os dados da empresa selecionada.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/companies">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
      </RouterLink>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Carregando dados da empresa..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="updateCompany">
      <div class="card-body">
        <CompanyFormInputs v-model="form" :errors="validationErrors" />
      </div>

      <div class="card-footer bg-white d-flex justify-content-between">
        <button
          type="button"
          class="btn btn-outline-danger"
          :disabled="isDeleting"
          @click="deleteCompany"
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
  </section>
</template>
