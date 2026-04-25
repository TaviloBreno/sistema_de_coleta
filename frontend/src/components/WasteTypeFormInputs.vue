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
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.name }"
        type="text"
        :value="modelValue.name"
        @input="updateField('name', $event.target.value)"
      />
      <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
    </div>

    <div class="col-md-3">
      <label class="form-label">Unidade</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.unit }"
        type="text"
        :value="modelValue.unit"
        @input="updateField('unit', $event.target.value)"
      />
      <div v-if="errors.unit" class="invalid-feedback">{{ errors.unit }}</div>
    </div>

    <div class="col-md-3 d-flex align-items-end">
      <div class="form-check">
        <input
          id="is_hazardous"
          class="form-check-input"
          type="checkbox"
          :checked="Boolean(modelValue.is_hazardous)"
          @change="updateField('is_hazardous', $event.target.checked ? 1 : 0)"
        />
        <label for="is_hazardous" class="form-check-label">Residuo perigoso</label>
      </div>
    </div>

    <div class="col-12">
      <label class="form-label">Descricao</label>
      <textarea
        class="form-control"
        :class="{ 'is-invalid': errors.description }"
        rows="3"
        :value="modelValue.description"
        @input="updateField('description', $event.target.value)"
      ></textarea>
      <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
    </div>
  </div>
</template>
