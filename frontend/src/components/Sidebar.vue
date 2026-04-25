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
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link" to="/companies">
        <i class="bi bi-buildings me-2"></i>Empresas
      </RouterLink>
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link" to="/collection-routes">
        <i class="bi bi-signpost-2 me-2"></i>Rotas de Coleta
      </RouterLink>
      <RouterLink class="nav-link sidebar-link" to="/interactive-map">
        <i class="bi bi-map me-2"></i>Mapa Interativo
      </RouterLink>
      <RouterLink class="nav-link sidebar-link" to="/collection-analytics">
        <i class="bi bi-bar-chart me-2"></i>Graficos e Relatorios
      </RouterLink>
      <RouterLink class="nav-link sidebar-link" to="/collection-requests">
        <i class="bi bi-inboxes me-2"></i>Solicitacoes
      </RouterLink>
      <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link" to="/waste-types">
        <i class="bi bi-recycle me-2"></i>Tipos de Residuos
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
}

.sidebar-link {
  color: #198754;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.sidebar-link:hover,
.sidebar-link.router-link-active {
  background: rgba(25, 135, 84, 0.12);
  color: #146c43;
}
</style>
