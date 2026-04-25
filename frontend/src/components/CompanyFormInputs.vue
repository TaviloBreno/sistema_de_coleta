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
      <label class="form-label">Nome da empresa</label>
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
      <label class="form-label">Telefone</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.phone }"
        type="text"
        :value="modelValue.phone"
        @input="updateField('phone', $event.target.value)"
      />
      <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone }}</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">E-mail</label>
      <input
        class="form-control"
        :class="{ 'is-invalid': errors.email }"
        type="email"
        :value="modelValue.email"
        @input="updateField('email', $event.target.value)"
      />
      <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
    </div>

    <div class="col-md-6">
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
  </div>
</template>
