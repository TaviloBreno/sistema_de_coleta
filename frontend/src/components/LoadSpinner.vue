<script setup>
defineProps({
  label: {
    type: String,
    default: 'Carregando...',
  },
})
</script>

<template>
  <div class="d-flex flex-column align-items-center justify-content-center py-5 gap-3">
    <div class="spinner-border text-success" role="status" aria-hidden="true"></div>
    <span class="text-muted">{{ label }}</span>
  </div>
</template>
