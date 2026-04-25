<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/Sidebar.vue'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-vh-100 bg-body-tertiary">
    <Navbar />

    <div v-if="authStore.isAuthenticated" class="d-flex">
      <Sidebar class="d-none d-md-block flex-shrink-0" />
      
      <main class="flex-grow-1 p-3">
        <RouterView />
      </main>
    </div>

    <main v-else>
      <RouterView />
    </main>
  </div>
</template>
