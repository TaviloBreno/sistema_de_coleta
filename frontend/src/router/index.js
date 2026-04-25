import { createRouter, createWebHistory } from 'vue-router'

import CompaniesListView from '@/views/CompaniesListView.vue'
import CompanyDetailsView from '@/views/CompanyDetailsView.vue'
import CompanyCreateView from '@/views/CompanyCreateView.vue'
import CollectionRoutesView from '@/views/CollectionRoutesView.vue'
import CollectionRouteCreateView from '@/views/CollectionRouteCreateView.vue'
import CollectionRouteDetailsView from '@/views/CollectionRouteDetailsView.vue'

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
    },
    {
      path: '/companies/new',
      name: 'companies-new',
      component: CompanyCreateView,
    },
    {
      path: '/companies/:id',
      name: 'companies-details',
      component: CompanyDetailsView,
      props: true,
    },
    {
      path: '/collection-routes',
      name: 'collection-routes-list',
      component: CollectionRoutesView,
    },
    {
      path: '/collection-routes/new',
      name: 'collection-routes-new',
      component: CollectionRouteCreateView,
    },
    {
      path: '/collection-routes/:id',
      name: 'collection-routes-details',
      component: CollectionRouteDetailsView,
      props: true,
    },
  ],
})

export default router
