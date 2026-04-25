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
})

const emit = defineEmits(['update:modelValue'])

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
      <label class="form-label">Rota de coleta</label>
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
      <label class="form-label">Nome do ponto</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.name }"
        type="text"
        :value="modelValue.name"
        @input="updateField('name', $event.target.value)"
      />
      <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
    </div>

    <div class="col-12">
      <label class="form-label">Endereco</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.address }"
        type="text"
        :value="modelValue.address"
        @input="updateField('address', $event.target.value)"
      />
      <div v-if="errors.address" class="invalid-feedback">{{ errors.address }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Contato</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.contact_name }"
        type="text"
        :value="modelValue.contact_name"
        @input="updateField('contact_name', $event.target.value)"
      />
      <div v-if="errors.contact_name" class="invalid-feedback">{{ errors.contact_name }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Telefone</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.contact_phone }"
        type="text"
        :value="modelValue.contact_phone"
        @input="updateField('contact_phone', $event.target.value)"
      />
      <div v-if="errors.contact_phone" class="invalid-feedback">{{ errors.contact_phone }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Horario</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.scheduled_time }"
        type="time"
        :value="modelValue.scheduled_time"
        @input="updateField('scheduled_time', $event.target.value)"
      />
      <div v-if="errors.scheduled_time" class="invalid-feedback">{{ errors.scheduled_time }}</div>
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
