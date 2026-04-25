<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/Sidebar.vue'
import Footer from '@/components/Footer.vue'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-vh-100 bg-body-tertiary d-flex flex-column">
    <Navbar />

    <div v-if="authStore.isAuthenticated" class="d-flex flex-grow-1">
      <Sidebar class="d-none d-md-block flex-shrink-0" />
      
      <div class="flex-grow-1 d-flex flex-column">
        <main class="p-3 flex-grow-1">
          <RouterView />
        </main>
        <Footer />
      </div>
    </div>

    <div v-else class="flex-grow-1 d-flex flex-column">
      <main class="flex-grow-1">
        <RouterView />
      </main>
      <Footer v-if="$route.path !== '/login' && $route.path !== '/register'" />
    </div>
  </div>
</template>
