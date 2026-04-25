import { createRouter, createWebHistory } from 'vue-router'

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
    {
      path: '/collection-points/new',
      name: 'collection-points-new',
      component: CollectionPointCreateView,
    },
    {
      path: '/collection-points/:id',
      name: 'collection-points-details',
      component: CollectionPointDetailsView,
      props: true,
    },
    {
      path: '/waste-types',
      name: 'waste-types-list',
      component: WasteTypesView,
    },
    {
      path: '/waste-types/new',
      name: 'waste-types-new',
      component: WasteTypeCreateView,
    },
    {
      path: '/waste-types/:id',
      name: 'waste-types-details',
      component: WasteTypeDetailsView,
      props: true,
    },
    {
      path: '/collection-records/:id',
      name: 'collection-records-details',
      component: CollectionRecordDetailsView,
      props: true,
    },
  ],
})

export default router
