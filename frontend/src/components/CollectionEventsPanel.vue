<script setup>
import { onMounted, ref } from 'vue'
import { toast } from 'vue3-toastify'

import collectionEventsApi from '@/api/CollectionEventsApi'

const props = defineProps({
  recordId: {
    type: Number,
    required: true,
  },
})

const events = ref([])
const isLoading = ref(false)
const isSaving = ref(false)
const form = ref({
  event_type: 'andamento',
  title: '',
  description: '',
  event_at: '',
})

const eventTypes = [
  { value: 'iniciado', label: 'Iniciado' },
  { value: 'andamento', label: 'Andamento' },
  { value: 'concluido', label: 'Concluido' },
  { value: 'ocorrencia', label: 'Ocorrencia' },
]

function toApiDate(value) {
  if (!value) {
    return ''
  }

  return `${value.replace('T', ' ')}:00`
}

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
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao processar evento de coleta.'
}

async function fetchEvents() {
  isLoading.value = true

  try {
    events.value = await collectionEventsApi.list(props.recordId)
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isLoading.value = false
  }
}

async function createEvent() {
  isSaving.value = true

  const payload = {
    collection_record_id: props.recordId,
    ...form.value,
    event_at: toApiDate(form.value.event_at),
  }

  try {
    const eventCreated = await collectionEventsApi.create(payload)
    events.value = [eventCreated, ...events.value]
    form.value = {
      event_type: 'andamento',
      title: '',
      description: '',
      event_at: '',
    }
    toast.success('Evento registrado com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  } finally {
    isSaving.value = false
  }
}

async function deleteEvent(item) {
  if (!window.confirm(`Deseja excluir o evento ${item.title}?`)) {
    return
  }

  try {
    await collectionEventsApi.destroy(item.id)
    events.value = events.value.filter((event) => event.id !== item.id)
    toast.success('Evento excluido com sucesso.')
  } catch (error) {
    toast.error(getErrorMessage(error))
  }
}

onMounted(fetchEvents)
</script>

<template>
  <div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white">
      <h3 class="h5 mb-0">Eventos da coleta</h3>
    </div>

    <div class="card-body border-bottom">
      <form class="row g-2" @submit.prevent="createEvent">
        <div class="col-md-3">
          <select class="form-select" v-model="form.event_type">
            <option v-for="item in eventTypes" :key="item.value" :value="item.value">{{ item.label }}</option>
          </select>
        </div>
        <div class="col-md-3">
          <input class="form-control" v-model="form.title" type="text" placeholder="Titulo" required />
        </div>
        <div class="col-md-3">
          <input class="form-control" v-model="form.event_at" type="datetime-local" required />
        </div>
        <div class="col-md-3 d-grid">
          <button class="btn btn-success" type="submit" :disabled="isSaving">
            Registrar evento
          </button>
        </div>
        <div class="col-12">
          <textarea class="form-control" rows="2" placeholder="Descricao" v-model="form.description"></textarea>
        </div>
      </form>
    </div>

    <ul class="list-group list-group-flush" v-if="!isLoading && events.length">
      <li class="list-group-item d-flex justify-content-between align-items-start" v-for="item in events" :key="item.id">
        <div>
          <div class="fw-semibold">{{ item.title }}</div>
          <div class="small text-muted">{{ formatDate(item.event_at) }} | {{ item.event_type }}</div>
          <div class="small" v-if="item.description">{{ item.description }}</div>
        </div>
        <button class="btn btn-outline-danger btn-sm" @click="deleteEvent(item)">
          <i class="bi bi-trash"></i>
        </button>
      </li>
    </ul>

    <div class="card-body text-muted" v-if="!isLoading && !events.length">Nenhum evento registrado.</div>
    <div class="card-body text-muted" v-if="isLoading">Carregando eventos...</div>
  </div>
</template>
