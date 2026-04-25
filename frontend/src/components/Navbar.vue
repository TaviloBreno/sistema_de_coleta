<script setup>
import { computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'

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
  <nav v-if="authStore.isAuthenticated" class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container">
      <RouterLink class="navbar-brand d-flex align-items-center gap-2" to="/companies">
        <i class="bi bi-recycle"></i>
        <span>Sistema de Coleta</span>
      </RouterLink>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
        aria-controls="mainNavbar"
        aria-expanded="false"
        aria-label="Alternar navegacao"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="mainNavbar" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li v-if="canManageCatalog" class="nav-item">
            <RouterLink class="nav-link" to="/companies">Empresas</RouterLink>
          </li>
          <li v-if="canManageCatalog" class="nav-item">
            <RouterLink class="nav-link" to="/collection-routes">Rotas de Coleta</RouterLink>
          </li>
          <li v-if="canManageCatalog" class="nav-item">
            <RouterLink class="nav-link" to="/waste-types">Tipos de Residuos</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" to="/collection-requests">Solicitacoes</RouterLink>
          </li>
          <li v-if="canManageCatalog" class="nav-item">
            <RouterLink class="nav-link" to="/companies/new">Nova Empresa</RouterLink>
          </li>
          <li class="nav-item">
            <button class="btn btn-link nav-link" type="button" @click="logout">Sair</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
