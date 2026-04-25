<script setup>
const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
  routes: {
    type: Array,
    default: () => [],
  },
  points: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const statuses = [
  { value: 'pendente', label: 'Pendente' },
  { value: 'aprovada', label: 'Aprovada' },
  { value: 'em_andamento', label: 'Em andamento' },
  { value: 'concluida', label: 'Concluida' },
  { value: 'cancelada', label: 'Cancelada' },
]

function updateField(field, value) {
  emit('update:modelValue', {
    ...props.modelValue,
    [field]: value,
  })
}
</script>

<template>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Rota</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors.collection_route_id }"
        :value="modelValue.collection_route_id"
        @change="updateField('collection_route_id', Number($event.target.value))"
      >
        <option :value="0">Selecione...</option>
        <option v-for="route in routes" :key="route.id" :value="route.id">{{ route.name }}</option>
      </select>
      <div v-if="errors.collection_route_id" class="invalid-feedback">{{ errors.collection_route_id }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Ponto de coleta</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors.collection_point_id }"
        :value="modelValue.collection_point_id"
        @change="updateField('collection_point_id', Number($event.target.value))"
      >
        <option :value="0">Selecione...</option>
        <option v-for="point in points" :key="point.id" :value="point.id">{{ point.name }}</option>
      </select>
      <div v-if="errors.collection_point_id" class="invalid-feedback">{{ errors.collection_point_id }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Titulo</label>
      <input class="form-control" :class="{ 'is-invalid': errors.title }" type="text" :value="modelValue.title" @input="updateField('title', $event.target.value)" />
      <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Status</label>
      <select class="form-select" :class="{ 'is-invalid': errors.status }" :value="modelValue.status" @change="updateField('status', $event.target.value)">
        <option v-for="status in statuses" :key="status.value" :value="status.value">{{ status.label }}</option>
      </select>
      <div v-if="errors.status" class="invalid-feedback">{{ errors.status }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Data programada</label>
      <input class="form-control" :class="{ 'is-invalid': errors.scheduled_at }" type="datetime-local" :value="modelValue.scheduled_at" @input="updateField('scheduled_at', $event.target.value)" />
      <div v-if="errors.scheduled_at" class="invalid-feedback">{{ errors.scheduled_at }}</div>
    </div>

    <div class="col-12">
      <label class="form-label">Descricao</label>
      <textarea class="form-control" :class="{ 'is-invalid': errors.description }" rows="3" :value="modelValue.description" @input="updateField('description', $event.target.value)"></textarea>
      <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
    </div>
  </div>
</template>
