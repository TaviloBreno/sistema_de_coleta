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
  wasteTypes: {
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
      <label class="form-label">Tipo de residuo</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors.waste_type_id }"
        :value="modelValue.waste_type_id"
        @change="updateField('waste_type_id', Number($event.target.value))"
      >
        <option :value="0">Selecione...</option>
        <option v-for="type in wasteTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
      </select>
      <div v-if="errors.waste_type_id" class="invalid-feedback">{{ errors.waste_type_id }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Quantidade</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.quantity }"
        type="number"
        step="0.01"
        min="0"
        :value="modelValue.quantity"
        @input="updateField('quantity', $event.target.value)"
      />
      <div v-if="errors.quantity" class="invalid-feedback">{{ errors.quantity }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Data/hora coleta</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.collected_at }"
        type="datetime-local"
        :value="modelValue.collected_at"
        @input="updateField('collected_at', $event.target.value)"
      />
      <div v-if="errors.collected_at" class="invalid-feedback">{{ errors.collected_at }}</div>
    </div>

    <div class="col-12">
      <label class="form-label">Observacoes</label>
      <textarea
        class="form-control"
        :class="{ 'is-invalid': errors.notes }"
        rows="2"
        :value="modelValue.notes"
        @input="updateField('notes', $event.target.value)"
      ></textarea>
      <div v-if="errors.notes" class="invalid-feedback">{{ errors.notes }}</div>
    </div>
  </div>
</template>
