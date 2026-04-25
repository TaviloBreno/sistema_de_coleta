<script setup>
defineProps({
  routes: {
    type: Array,
    default: () => [],
  },
  title: {
    type: String,
    default: 'Rotas de Coleta',
  },
  showCompany: {
    type: Boolean,
    default: true,
  },
  emptyMessage: {
    type: String,
    default: 'Nenhuma rota cadastrada.',
  },
})

const emit = defineEmits(['delete'])

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

function statusClass(status) {
  const classes = {
    pendente: 'text-bg-warning',
    em_rota: 'text-bg-primary',
    concluida: 'text-bg-success',
    cancelada: 'text-bg-secondary',
  }

  return classes[status] || 'text-bg-light'
}

function statusLabel(status) {
  const labels = {
    pendente: 'Pendente',
    em_rota: 'Em rota',
    concluida: 'Concluida',
    cancelada: 'Cancelada',
  }

  return labels[status] || status
}
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex align-items-center justify-content-between">
      <h2 class="h5 mb-0">{{ title }}</h2>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Rota</th>
            <th v-if="showCompany">Empresa</th>
            <th>Inicio</th>
            <th>Fim</th>
            <th>Agendamento</th>
            <th>Status</th>
            <th class="text-end">Acoes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!routes.length">
            <td :colspan="showCompany ? 7 : 6" class="text-center py-4 text-muted">{{ emptyMessage }}</td>
          </tr>
          <tr v-for="item in routes" :key="item.id">
            <td>{{ item.name }}</td>
            <td v-if="showCompany">{{ item.company_name || `#${item.company_id}` }}</td>
            <td>{{ item.start_point }}</td>
            <td>{{ item.end_point }}</td>
            <td>{{ formatDate(item.scheduled_at) }}</td>
            <td>
              <span class="badge" :class="statusClass(item.status)">{{ statusLabel(item.status) }}</span>
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <RouterLink :to="`/collection-routes/${item.id}`" class="btn btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </RouterLink>
                <button class="btn btn-outline-danger" @click="emit('delete', item)">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
