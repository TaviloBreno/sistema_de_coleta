<script setup>
import Navbar from '@/components/Navbar.vue'
</script>

<template>
  <div class="min-vh-100 bg-body-tertiary">
    <Navbar />
    <main>
      <RouterView />
    </main>
  </div>
</template>
