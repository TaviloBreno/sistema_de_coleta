<script setup>
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'

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
  <aside 
    v-if="authStore.isAuthenticated" 
    class="sidebar-panel h-100 border-end bg-white transition-all"
    :class="{ 'collapsed': layoutStore.isSidebarCollapsed }"
  >
    <div class="p-3">
      <h2 v-if="!layoutStore.isSidebarCollapsed" class="h6 text-uppercase text-muted mb-3">Menu</h2>

      <nav class="nav flex-column gap-1">
        <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/companies" title="Empresas">
          <i class="bi bi-buildings fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Empresas</span>
        </RouterLink>
        <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/collection-routes" title="Rotas de Coleta">
          <i class="bi bi-signpost-2 fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Rotas de Coleta</span>
        </RouterLink>
        <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/interactive-map" title="Mapa Interativo">
          <i class="bi bi-map fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Mapa Interativo</span>
        </RouterLink>
        <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/collection-analytics" title="Graficos e Relatorios">
          <i class="bi bi-bar-chart fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Graficos e Relatorios</span>
        </RouterLink>
        <RouterLink class="nav-link sidebar-link d-flex align-items-center" to="/collection-requests" title="Solicitacoes">
          <i class="bi bi-inboxes fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Solicitacoes</span>
        </RouterLink>
        <RouterLink v-if="canManageCatalog" class="nav-link sidebar-link d-flex align-items-center" to="/waste-types" title="Tipos de Residuos">
          <i class="bi bi-recycle fs-5 flex-shrink-0" :class="{ 'me-2': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed" class="text-truncate">Tipos de Residuos</span>
        </RouterLink>
      </nav>

      <div class="mt-4 pt-3 border-top">
        <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center" type="button" @click="logout" title="Sair">
          <i class="bi bi-box-arrow-right fs-5 flex-shrink-0" :class="{ 'me-1': !layoutStore.isSidebarCollapsed }"></i>
          <span v-if="!layoutStore.isSidebarCollapsed">Sair</span>
        </button>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.sidebar-panel {
  min-height: calc(100vh - 64px);
  overflow-x: hidden;
  width: 250px;
  transition: width 0.3s ease;
}

.sidebar-panel.collapsed {
  width: 80px;
}

.transition-all {
  transition: all 0.3s ease;
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
