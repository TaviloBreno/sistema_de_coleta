<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import companiesApi from '@/api/CompaniesApi'
import CompanyFormInputs from '@/components/CompanyFormInputs.vue'

const router = useRouter()

const isSaving = ref(false)
const validationErrors = ref({})
const form = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
})

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao criar a empresa.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function createCompany() {
  isSaving.value = true
  validationErrors.value = {}

  try {
    const companyCreated = await companiesApi.create(form.value)
    const companyId = companyCreated?.id

    if (!companyId) {
      throw new Error('Resposta de criacao sem ID.')
    }

    toast.success('Empresa criada com sucesso.')
    router.push(`/companies/${companyId}`)
  } catch (error) {
    validationErrors.value = getValidationErrors(error)
    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Nova empresa</h1>
        <p class="text-muted mb-0">Preencha os dados para cadastrar uma nova empresa.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/companies">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
      </RouterLink>
    </div>

    <form class="card shadow-sm border-0" @submit.prevent="createCompany">
      <div class="card-body">
        <CompanyFormInputs v-model="form" :errors="validationErrors" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-success" :disabled="isSaving">
          <span v-if="isSaving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          Criar empresa
        </button>
      </div>
    </form>
  </section>
</template>
