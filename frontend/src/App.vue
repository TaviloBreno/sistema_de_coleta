<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/Sidebar.vue'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-vh-100 bg-body-tertiary">
    <Navbar />

    <div v-if="authStore.isAuthenticated" class="container-fluid">
      <div class="row">
        <div class="d-none d-md-block col-md-3 col-lg-2 px-0">
          <Sidebar />
        </div>
        <main class="col-12 col-md-9 col-lg-10">
          <RouterView />
        </main>
      </div>
    </div>

    <main v-else>
      <RouterView />
    </main>
  </div>
</template>
