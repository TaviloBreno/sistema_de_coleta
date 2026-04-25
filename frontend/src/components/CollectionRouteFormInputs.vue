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
  companies: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const statuses = [
  { value: 'pendente', label: 'Pendente' },
  { value: 'em_rota', label: 'Em rota' },
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
      <label class="form-label">Empresa</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors.company_id }"
        :value="modelValue.company_id"
        @change="updateField('company_id', Number($event.target.value))"
      >
        <option :value="0">Selecione...</option>
        <option v-for="company in companies" :key="company.id" :value="company.id">
          {{ company.name }}
        </option>
      </select>
      <div v-if="errors.company_id" class="invalid-feedback">{{ errors.company_id }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nome da rota</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.name }"
        type="text"
        :value="modelValue.name"
        @input="updateField('name', $event.target.value)"
      />
      <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Ponto de inicio</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.start_point }"
        type="text"
        :value="modelValue.start_point"
        @input="updateField('start_point', $event.target.value)"
      />
      <div v-if="errors.start_point" class="invalid-feedback">{{ errors.start_point }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Ponto final</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.end_point }"
        type="text"
        :value="modelValue.end_point"
        @input="updateField('end_point', $event.target.value)"
      />
      <div v-if="errors.end_point" class="invalid-feedback">{{ errors.end_point }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Data e horario</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.scheduled_at }"
        type="datetime-local"
        :value="modelValue.scheduled_at"
        @input="updateField('scheduled_at', $event.target.value)"
      />
      <div v-if="errors.scheduled_at" class="invalid-feedback">{{ errors.scheduled_at }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Status</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors.status }"
        :value="modelValue.status"
        @change="updateField('status', $event.target.value)"
      >
        <option v-for="status in statuses" :key="status.value" :value="status.value">
          {{ status.label }}
        </option>
      </select>
      <div v-if="errors.status" class="invalid-feedback">{{ errors.status }}</div>
    </div>

    <div class="col-12">
      <label class="form-label">Observacoes</label>
      <textarea
        class="form-control"
        :class="{ 'is-invalid': errors.notes }"
        rows="3"
        :value="modelValue.notes"
        @input="updateField('notes', $event.target.value)"
      ></textarea>
      <div v-if="errors.notes" class="invalid-feedback">{{ errors.notes }}</div>
    </div>
  </div>
</template>
