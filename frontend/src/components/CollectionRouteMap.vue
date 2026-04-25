<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import L from 'leaflet'

import { crateusCityCenter, crateusPointCoordinates, crateusRouteCoordinates } from '@/data/crateusMap'

const props = defineProps({
  routeName: {
    type: String,
    required: true,
  },
  points: {
    type: Array,
    default: () => [],
  },
  showLegend: {
    type: Boolean,
    default: true,
  },
})

const mapContainer = ref(null)
let mapInstance = null
let markersLayer = null
let polylineLayer = null

const routePath = computed(() => {
  const predefined = crateusRouteCoordinates[props.routeName] || []

  if (predefined.length) {
    return predefined
  }

  const dynamicPoints = props.points
    .map((item) => ({
      ...item,
      coordinates: crateusPointCoordinates[item.name],
    }))
    .filter((item) => item.coordinates)
    .map((item) => ({
      lat: item.coordinates.lat,
      lng: item.coordinates.lng,
      label: item.name,
    }))

  return [
    { lat: crateusCityCenter.lat, lng: crateusCityCenter.lng, label: crateusCityCenter.label },
    ...dynamicPoints,
  ]
})

function createMarkerIcon(type) {
  const classes = {
    start: 'route-map-marker route-map-marker-start',
    point: 'route-map-marker route-map-marker-point',
    end: 'route-map-marker route-map-marker-end',
  }

  return L.divIcon({
    className: '',
    html: `<div class="${classes[type]}"></div>`,
    iconSize: [24, 24],
    iconAnchor: [12, 24],
  })
}

function renderMap() {
  if (!mapContainer.value) {
    return
  }

  if (!mapInstance) {
    mapInstance = L.map(mapContainer.value, {
      zoomControl: true,
      scrollWheelZoom: false,
    }).setView([crateusCityCenter.lat, crateusCityCenter.lng], 13)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(mapInstance)

    markersLayer = L.layerGroup().addTo(mapInstance)
    polylineLayer = L.layerGroup().addTo(mapInstance)
  }

  markersLayer.clearLayers()
  polylineLayer.clearLayers()

  const path = routePath.value
  const latLngs = path.map((item) => [item.lat, item.lng])

  if (!latLngs.length) {
    return
  }

  const polyline = L.polyline(latLngs, {
    color: '#16a34a',
    weight: 5,
    opacity: 0.9,
    lineCap: 'round',
    dashArray: '8 8',
  })

  polyline.addTo(polylineLayer)

  path.forEach((item, index) => {
    const isFirst = index === 0
    const isLast = index === path.length - 1
    const marker = L.marker([item.lat, item.lng], {
      icon: createMarkerIcon(isFirst ? 'start' : isLast ? 'end' : 'point'),
    })

    marker.bindPopup(`<strong>${item.label}</strong><br>${props.routeName}<br>${crateusCityCenter.label}`)
    marker.addTo(markersLayer)
  })

  mapInstance.fitBounds(polyline.getBounds().pad(0.25))
}

onMounted(async () => {
  await nextTick()
  renderMap()
})

watch(
  () => [props.routeName, props.points],
  async () => {
    await nextTick()
    renderMap()
  },
  { deep: true }
)

onBeforeUnmount(() => {
  if (mapInstance) {
    mapInstance.remove()
    mapInstance = null
  }
})
</script>

<template>
  <div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h2 class="h5 mb-0">Mapa da passagem</h2>
      <span class="text-muted small">Crateús - CE</span>
    </div>
    <div ref="mapContainer" class="route-map"></div>
    <div v-if="showLegend" class="card-body pt-2">
      <div class="d-flex flex-wrap gap-3 small text-muted">
        <span><span class="legend-dot legend-start"></span> Saida da base</span>
        <span><span class="legend-dot legend-point"></span> Ponto da rota</span>
        <span><span class="legend-dot legend-end"></span> Final da passagem</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.route-map {
  height: 420px;
  width: 100%;
}

.route-map-marker {
  width: 24px;
  height: 24px;
  border-radius: 50% 50% 50% 0;
  transform: rotate(-45deg);
  border: 3px solid #fff;
  box-shadow: 0 10px 18px rgba(0, 0, 0, 0.18);
}

.route-map-marker-start {
  background: #16a34a;
}

.route-map-marker-point {
  background: #2563eb;
}

.route-map-marker-end {
  background: #dc2626;
}

.legend-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 999px;
  margin-right: 6px;
}

.legend-start {
  background: #16a34a;
}

.legend-point {
  background: #2563eb;
}

.legend-end {
  background: #dc2626;
}
</style>
