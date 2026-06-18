<script setup>
import { ref } from 'vue'
import Edishio from './Edishio.vue'

// Recibe una única promoción como objeto desde el padre
const props = defineProps({
  promocion: Object,
  mostrarEditar: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['editar'])

const mostrarCompleto = ref(false)

const formatDateTime = (datetime) => {
  if (!datetime) return ''
  const date = new Date(datetime)
  return date.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const reclamarOferta = (promo) => {
  console.log('Oferta reclamada:', promo.titulo)
  alert('¡Has reclamado la oferta exitosamente!')
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg overflow-hidden relative group">
    <div class="p-6 pb-4 flex justify-between items-start gap-4">
      <h2 class="text-xl font-bold text-gray-900 leading-tight">
        {{ promocion.titulo }}
      </h2>
      <Edishio v-if="mostrarEditar" @click="emit('editar', promocion)" />
    </div>

    <div class="relative px-6">
      <div class="relative rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
        <img
          :src="promocion.foto ? '/storage/' + promocion.foto : '/placeholder-promo.jpg'"
          alt="Promoción"
          class="w-full h-56 object-contain"
        />
        <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
          PROMOCIÓN ACTIVA
        </span>
      </div>
    </div>

    <div class="px-6 py-4 grid grid-cols-2 gap-4">
      <div class="flex items-start space-x-2">
        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <div>
          <p class="text-xs text-gray-500 uppercase font-medium">Inicia:</p>
          <p class="text-sm font-semibold text-gray-900">{{ formatDateTime(promocion.fecha_inicio) }}</p>
        </div>
      </div>
      <div class="flex items-start space-x-2">
        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <div>
          <p class="text-xs text-gray-500 uppercase font-medium">Finaliza:</p>
          <p class="text-sm font-semibold text-gray-900">{{ formatDateTime(promocion.fecha_fin) }}</p>
        </div>
      </div>
    </div>

    <div class="px-6 pb-6">
      <p class="text-gray-600 text-sm leading-relaxed">
        {{ mostrarCompleto ? promocion.descripcion : (promocion.descripcion.length > 120 ? promocion.descripcion.substring(0, 120) + '...' : promocion.descripcion) }}
        <button
          v-if="promocion.descripcion.length > 120"
          @click="mostrarCompleto = !mostrarCompleto"
          class="text-blue-600 font-medium hover:text-blue-800 ml-1"
        >
          {{ mostrarCompleto ? 'menos' : 'más' }}
        </button>
      </p>

      <button
        @click="reclamarOferta(promocion)"
        class="w-full mt-6 bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
      >
        RECLAMAR OFERTA AHORA
      </button>
    </div>
  </div>
</template>