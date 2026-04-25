import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/useAuthStore'

import CompaniesListView from '@/views/CompaniesListView.vue'
import CompanyDetailsView from '@/views/CompanyDetailsView.vue'
import CompanyCreateView from '@/views/CompanyCreateView.vue'
import CollectionRoutesView from '@/views/CollectionRoutesView.vue'
import CollectionRouteCreateView from '@/views/CollectionRouteCreateView.vue'
import CollectionRouteDetailsView from '@/views/CollectionRouteDetailsView.vue'
import CollectionPointCreateView from '@/views/CollectionPointCreateView.vue'
import CollectionPointDetailsView from '@/views/CollectionPointDetailsView.vue'
import WasteTypesView from '@/views/WasteTypesView.vue'
import WasteTypeCreateView from '@/views/WasteTypeCreateView.vue'
import WasteTypeDetailsView from '@/views/WasteTypeDetailsView.vue'
import CollectionRecordDetailsView from '@/views/CollectionRecordDetailsView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import CollectionRequestsView from '@/views/CollectionRequestsView.vue'
import CollectionRequestCreateView from '@/views/CollectionRequestCreateView.vue'
import CollectionRequestDetailsView from '@/views/CollectionRequestDetailsView.vue'
import InteractiveMapView from '@/views/InteractiveMapView.vue'
import CollectionAnalyticsView from '@/views/CollectionAnalyticsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/companies',
    },
    {
      path: '/companies',
      name: 'companies-list',
      component: CompaniesListView,
      meta: { requiresAuth: true },
    },
    {
      path: '/companies/new',
      name: 'companies-new',
      component: CompanyCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/companies/:id',
      name: 'companies-details',
      component: CompanyDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-routes',
      name: 'collection-routes-list',
      component: CollectionRoutesView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-routes/new',
      name: 'collection-routes-new',
      component: CollectionRouteCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-routes/:id',
      name: 'collection-routes-details',
      component: CollectionRouteDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-points/new',
      name: 'collection-points-new',
      component: CollectionPointCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-points/:id',
      name: 'collection-points-details',
      component: CollectionPointDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/waste-types',
      name: 'waste-types-list',
      component: WasteTypesView,
      meta: { requiresAuth: true },
    },
    {
      path: '/waste-types/new',
      name: 'waste-types-new',
      component: WasteTypeCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/waste-types/:id',
      name: 'waste-types-details',
      component: WasteTypeDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-records/:id',
      name: 'collection-records-details',
      component: CollectionRecordDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-requests',
      name: 'collection-requests-list',
      component: CollectionRequestsView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-requests/new',
      name: 'collection-requests-new',
      component: CollectionRequestCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-requests/:id',
      name: 'collection-requests-details',
      component: CollectionRequestDetailsView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/interactive-map',
      name: 'interactive-map',
      component: InteractiveMapView,
      meta: { requiresAuth: true },
    },
    {
      path: '/collection-analytics',
      name: 'collection-analytics',
      component: CollectionAnalyticsView,
      meta: { requiresAuth: true },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guestOnly: true },
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { guestOnly: true },
    },
  ],
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore()
  await authStore.initialize()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return { name: 'companies-list' }
  }

  return true
})

export default router
