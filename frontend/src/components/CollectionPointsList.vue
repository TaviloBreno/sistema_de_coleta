<script setup>
defineProps({
  points: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['delete'])
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white">
      <h3 class="h5 mb-0">Pontos de coleta da rota</h3>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Ponto</th>
            <th>Endereco</th>
            <th>Contato</th>
            <th>Horario</th>
            <th class="text-end">Acoes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!points.length">
            <td colspan="5" class="text-center py-4 text-muted">Nenhum ponto cadastrado para esta rota.</td>
          </tr>
          <tr v-for="point in points" :key="point.id">
            <td>{{ point.name }}</td>
            <td>{{ point.address }}</td>
            <td>
              {{ point.contact_name || '-' }}
              <span class="text-muted" v-if="point.contact_phone">({{ point.contact_phone }})</span>
            </td>
            <td>{{ point.scheduled_time || '-' }}</td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <RouterLink :to="`/collection-points/${point.id}`" class="btn btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </RouterLink>
                <button class="btn btn-outline-danger" @click="emit('delete', point)">
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
