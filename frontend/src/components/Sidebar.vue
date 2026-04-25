<script setup>
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'

import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
const router = useRouter()

const canManageCatalog = computed(() => authStore.canManageCatalog)

async function logout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <aside v-if="authStore.isAuthenticated" class="sidebar-panel h-100 p-3 border-end bg-white">
    <h2 class="h6 text-uppercase text-muted mb-3">Menu</h2>

    <nav class="nav flex-column gap-1">
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/companies">
        <i class="bi bi-buildings me-2 flex-shrink-0"></i>
        <span class="text-truncate">Empresas</span>
      </RouterLink>
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/collection-routes">
        <i class="bi bi-signpost-2 me-2 flex-shrink-0"></i>
        <span class="text-truncate">Rotas de Coleta</span>
      </RouterLink>
      <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/interactive-map">
        <i class="bi bi-map me-2 flex-shrink-0"></i>
        <span class="text-truncate">Mapa Interativo</span>
      </RouterLink>
      <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/collection-analytics">
        <i class="bi bi-bar-chart me-2 flex-shrink-0"></i>
        <span class="text-truncate">Graficos e Relatorios</span>
      </RouterLink>
      <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/collection-requests">
        <i class="bi bi-inboxes me-2 flex-shrink-0"></i>
        <span class="text-truncate">Solicitacoes</span>
      </RouterLink>
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/waste-types">
        <i class="bi bi-recycle me-2 flex-shrink-0"></i>
        <span class="text-truncate">Tipos de Residuos</span>
      </RouterLink>
    </nav>

    <div class="mt-4 pt-3 border-top">
      <button class="btn btn-outline-danger w-100" type="button" @click="logout">
        <i class="bi bi-box-arrow-right me-1"></i>Sair
      </button>
    </div>
  </aside>
</template>

<style scoped>
.sidebar-panel {
  min-height: calc(100vh - 64px);
  overflow-x: hidden;
  width: 108%;
}

.sidebar-link {
  color: #198754;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  min-width: 0; 
}

.sidebar-link:hover,
.sidebar-link.router-link-active {
  background: rgba(25, 135, 84, 0.12);
  color: #146c43;
}
</style>
