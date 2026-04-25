<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import collectionRoutesApi from '@/api/CollectionRoutesApi'
import companiesApi from '@/api/CompaniesApi'
import CollectionRouteFormInputs from '@/components/CollectionRouteFormInputs.vue'
import LoadSpinner from '@/components/LoadSpinner.vue'

const router = useRouter()
const route = useRoute()

const isLoading = ref(false)
const isSaving = ref(false)
const companies = ref([])
const validationErrors = ref({})
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

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao criar a rota.'
}

function getValidationErrors(error) {
  const messages = error?.response?.data?.messages
  return messages && typeof messages === 'object' ? messages : {}
}

async function fetchCompanies() {
  isLoading.value = true

  try {
    companies.value = await companiesApi.list()

    const queryCompanyId = Number(route.query.company_id)
    const hasCompany = companies.value.some((company) => company.id === queryCompanyId)

    if (hasCompany) {
      form.value.company_id = queryCompanyId
    }
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isLoading.value = false
  }
}

async function createRoute() {
  isSaving.value = true
  validationErrors.value = {}

  const payload = {
    ...form.value,
    scheduled_at: toApiDate(form.value.scheduled_at),
  }

  try {
    const routeCreated = await collectionRoutesApi.create(payload)

    if (!routeCreated?.id) {
      throw new Error('Resposta de criacao sem ID.')
    }

    toast.success('Rota criada com sucesso.')
    router.push(`/collection-routes/${routeCreated.id}`)
  } catch (error) {
    validationErrors.value = getValidationErrors(error)

    if (!Object.keys(validationErrors.value).length) {
      toast.error(getErrorMessage(error))
    }
  } finally {
    isSaving.value = false
  }
}

onMounted(fetchCompanies)
</script>

<template>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Nova rota de coleta</h1>
        <p class="text-muted mb-0">Cadastre uma nova rota associada a uma empresa.</p>
      </div>
      <RouterLink class="btn btn-outline-secondary" to="/collection-routes">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
      </RouterLink>
    </div>

    <LoadSpinner v-if="isLoading" label="Carregando empresas..." />

    <form v-else class="card shadow-sm border-0" @submit.prevent="createRoute">
      <div class="card-body">
        <CollectionRouteFormInputs v-model="form" :errors="validationErrors" :companies="companies" />
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-success" :disabled="isSaving">
          <span v-if="isSaving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          Criar rota
        </button>
      </div>
    </form>
  </section>
</template>
