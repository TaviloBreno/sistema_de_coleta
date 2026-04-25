<script setup>
import { computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'

import { useAuthStore } from '@/stores/useAuthStore'
import { useLayoutStore } from '@/stores/useLayoutStore'

const authStore = useAuthStore()
const layoutStore = useLayoutStore()
const router = useRouter()

const canManageCatalog = computed(() => authStore.canManageCatalog)

async function logout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <nav v-if="authStore.isAuthenticated" class="navbar navbar-expand navbar-dark bg-success shadow-sm">
    <div class="container-fluid">
      <div class="d-flex align-items-center">
        <button 
          class="btn btn-success me-3 p-1" 
          type="button" 
          @click="layoutStore.toggleSidebar"
          aria-label="Alternar sidebar"
        >
          <i class="bi bi-list fs-4"></i>
        </button>

        <RouterLink class="navbar-brand d-flex align-items-center flex-nowrap" to="/companies">
          <i class="bi bi-recycle flex-shrink-0 me-2"></i>
          <span class="text-truncate">Sistema de Coleta</span>
        </RouterLink>
      </div>

      <div class="ms-auto d-flex align-items-center">
        <span class="text-white-50 me-3 d-none d-sm-inline">Olá, {{ authStore.user?.name || 'Usuário' }}</span>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar-brand {
  display: flex;
  align-items: center;
  transition: font-size 0.2s ease;
}

.navbar-brand span {
  white-space: nowrap;
}

@media (max-width: 576px) {
  .navbar-brand {
    font-size: 1.1rem;
    max-width: 85%;
  }
  
  .navbar-brand i {
    font-size: 1.2rem;
  }
}

@media (max-width: 400px) {
  .navbar-brand {
    font-size: 1rem;
    max-width: 80%;
  }
}
</style>
