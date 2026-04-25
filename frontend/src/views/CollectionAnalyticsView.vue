<script setup>
import { computed, onMounted, ref } from 'vue'
import { Bar, Doughnut } from 'vue-chartjs'
import {
  ArcElement,
  BarElement,
  CategoryScale,
  Chart as ChartJS,
  Legend,
  LinearScale,
  Title,
  Tooltip,
} from 'chart.js'
import * as XLSX from 'xlsx'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

import collectionRecordsApi from '@/api/CollectionRecordsApi'
import collectionEventsApi from '@/api/CollectionEventsApi'
import collectionPointsApi from '@/api/CollectionPointsApi'
import wasteTypesApi from '@/api/WasteTypesApi'
import LoadSpinner from '@/components/LoadSpinner.vue'

ChartJS.register(CategoryScale, LinearScale, BarElement, ArcElement, Title, Tooltip, Legend)

const records = ref([])
const events = ref([])
const points = ref([])
const wasteTypes = ref([])
const isLoading = ref(false)
const errorMessage = ref('')

function getErrorMessage(error) {
  return error?.response?.data?.message || error?.response?.data?.error || 'Falha ao carregar indicadores.'
}

async function fetchData() {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const [recordsData, eventsData, pointsData, wasteData] = await Promise.all([
      collectionRecordsApi.list(),
      collectionEventsApi.list(),
      collectionPointsApi.list(),
      wasteTypesApi.list(),
    ])

    records.value = recordsData
    events.value = eventsData
    points.value = pointsData
    wasteTypes.value = wasteData
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}

const wasteMap = computed(() => {
  return wasteTypes.value.reduce((acc, item) => {
    acc[item.id] = item.name
    return acc
  }, {})
})

const pointMap = computed(() => {
  return points.value.reduce((acc, item) => {
    acc[item.id] = item.name
    return acc
  }, {})
})

const totalCollectedKg = computed(() => {
  return records.value.reduce((sum, item) => sum + Number(item.quantity || 0), 0)
})

const wasteChartData = computed(() => {
  const grouped = records.value.reduce((acc, item) => {
    const label = wasteMap.value[item.waste_type_id] || `Tipo #${item.waste_type_id}`
    acc[label] = (acc[label] || 0) + Number(item.quantity || 0)
    return acc
  }, {})

  return {
    labels: Object.keys(grouped),
    datasets: [
      {
        label: 'Quantidade (kg)',
        data: Object.values(grouped),
        backgroundColor: ['#16a34a', '#2563eb', '#f97316', '#dc2626', '#14b8a6'],
      },
    ],
  }
})

const eventsChartData = computed(() => {
  const grouped = events.value.reduce((acc, item) => {
    const label = item.event_type || 'indefinido'
    acc[label] = (acc[label] || 0) + 1
    return acc
  }, {})

  return {
    labels: Object.keys(grouped),
    datasets: [
      {
        label: 'Eventos',
        data: Object.values(grouped),
        backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#64748b'],
      },
    ],
  }
})

const recordsByPointChartData = computed(() => {
  const grouped = records.value.reduce((acc, item) => {
    const label = pointMap.value[item.collection_point_id] || `Ponto #${item.collection_point_id}`
    acc[label] = (acc[label] || 0) + 1
    return acc
  }, {})

  return {
    labels: Object.keys(grouped),
    datasets: [
      {
        label: 'Registros',
        data: Object.values(grouped),
        backgroundColor: '#15803d',
        borderRadius: 6,
      },
    ],
  }
})

const reportRows = computed(() => {
  return records.value.map((item) => ({
    id: item.id,
    ponto: pointMap.value[item.collection_point_id] || `Ponto #${item.collection_point_id}`,
    residuo: wasteMap.value[item.waste_type_id] || `Tipo #${item.waste_type_id}`,
    quantidade_kg: Number(item.quantity || 0),
    coletado_em: item.collected_at,
    observacoes: item.notes || '',
  }))
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
}

function openBlobInNewTab(blob) {
  const url = URL.createObjectURL(blob)
  window.open(url, '_blank', 'noopener,noreferrer')
  setTimeout(() => URL.revokeObjectURL(url), 4000)
}

function generateXlsReport() {
  const worksheet = XLSX.utils.json_to_sheet(reportRows.value)
  const workbook = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(workbook, worksheet, 'RelatorioColeta')
  const content = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' })
  const blob = new Blob([content], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  })

  openBlobInNewTab(blob)
}

function generatePdfReport() {
  const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' })

  doc.setFontSize(14)
  doc.text('Relatorio de Coleta - Crateus', 14, 12)

  autoTable(doc, {
    startY: 18,
    head: [['ID', 'Ponto', 'Residuo', 'Quantidade (kg)', 'Coletado em', 'Observacoes']],
    body: reportRows.value.map((item) => [
      item.id,
      item.ponto,
      item.residuo,
      String(item.quantidade_kg),
      item.coletado_em,
      item.observacoes,
    ]),
    styles: { fontSize: 8 },
    headStyles: { fillColor: [22, 163, 74] },
  })

  const blob = doc.output('blob')
  openBlobInNewTab(blob)
}

onMounted(fetchData)
</script>

<template>
  <section class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h1 class="h3 mb-1">Graficos e Relatorios</h1>
        <p class="text-muted mb-0">Acompanhe indicadores e gere relatorios de coleta.</p>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-success" type="button" @click="generateXlsReport">
          <i class="bi bi-file-earmark-excel me-1"></i>Gerar XLS
        </button>
        <button class="btn btn-success" type="button" @click="generatePdfReport">
          <i class="bi bi-file-earmark-pdf me-1"></i>Gerar PDF
        </button>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

    <LoadSpinner v-if="isLoading" label="Carregando graficos e indicadores..." />

    <template v-else>
      <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <p class="text-muted mb-1">Total coletado</p>
              <h2 class="h4 mb-0">{{ totalCollectedKg.toFixed(2) }} kg</h2>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <p class="text-muted mb-1">Registros de coleta</p>
              <h2 class="h4 mb-0">{{ records.length }}</h2>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <p class="text-muted mb-1">Eventos registrados</p>
              <h2 class="h4 mb-0">{{ events.length }}</h2>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-12 col-xl-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
              <h2 class="h5 mb-0">Quantidade por tipo de residuo</h2>
            </div>
            <div class="card-body">
              <div style="position: relative; height: 300px;">
                <Doughnut :data="wasteChartData" :options="chartOptions" />
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
              <h2 class="h5 mb-0">Eventos por tipo</h2>
            </div>
            <div class="card-body">
              <div style="position: relative; height: 300px;">
                <Doughnut :data="eventsChartData" :options="chartOptions" />
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h2 class="h5 mb-0">Registros por ponto de coleta</h2>
            </div>
            <div class="card-body">
              <div style="position: relative; height: 300px;">
                <Bar :data="recordsByPointChartData" :options="chartOptions" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </section>
</template>
