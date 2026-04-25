<script setup>
import { reactive } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { toast } from 'vue3-toastify'

import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
const router = useRouter()

const form = reactive({
  email: '',
  password: '',
})

async function submitLogin() {
  try {
    await authStore.login(form)
    toast.success('Login realizado com sucesso.')
    router.push('/companies')
  } catch (error) {
    toast.error(error?.response?.data?.message || 'Nao foi possivel realizar o login.')
  }
}
</script>

<template>
  <section class="auth-page d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-lg border-0 auth-card">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <div class="display-6 text-success mb-2"><i class="bi bi-shield-lock"></i></div>
          <h1 class="h3 mb-1">Entrar no sistema</h1>
          <p class="text-muted mb-0">Acesse sua conta para continuar.</p>
        </div>

        <form class="d-grid gap-3" @submit.prevent="submitLogin">
          <div>
            <label class="form-label">E-mail</label>
            <input v-model="form.email" type="email" class="form-control form-control-lg" required />
          </div>

          <div>
            <label class="form-label">Senha</label>
            <input v-model="form.password" type="password" class="form-control form-control-lg" required />
          </div>

          <button type="submit" class="btn btn-success btn-lg" :disabled="authStore.loading">
            <span v-if="authStore.loading" class="spinner-border spinner-border-sm me-2"></span>
            Entrar
          </button>
        </form>

        <p class="text-center mt-4 mb-0">
          Nao tem conta?
          <RouterLink to="/register">Criar conta</RouterLink>
        </p>
      </div>
    </div>
  </section>
</template>

<style scoped>
.auth-page {
  min-height: calc(100vh - 72px);
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(34, 197, 94, 0.08));
}

.auth-card {
  width: min(100%, 460px);
}
</style>
