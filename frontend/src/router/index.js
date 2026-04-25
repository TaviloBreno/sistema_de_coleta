import { createRouter, createWebHistory } from 'vue-router'

import CompaniesListView from '@/views/CompaniesListView.vue'
import CompanyDetailsView from '@/views/CompanyDetailsView.vue'
import CompanyCreateView from '@/views/CompanyCreateView.vue'

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
  ],
})

export default router
