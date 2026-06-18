<script setup>
import Menucreador from './Dcomponents/Menucreador.vue'
import TarjetaPromocion from './Dcomponents/TarjetaPromocion.vue'
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  promociones: Array,
  misNegociosIds: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
  <div class="fixed inset-0 flex flex-col bg-gray-50 overflow-hidden">
    <Header />

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <Menucreador class="hidden md:flex" />

      <main class="flex-1 p-6 overflow-y-auto">
      <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">🔥 Promociones</h1>
            <p class="text-gray-500 text-sm mt-1">Explora todas las ofertas y edita tus propias promociones en un solo lugar.</p>
          </div>
        </div>

        <div v-if="promociones && promociones.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <TarjetaPromocion
            v-for="promocion in promociones"
            :key="promocion.id"
            :promocion="promocion"
            :mostrarEditar="false"
          />
        </div>
        <div v-else class="text-center py-16 bg-white rounded-2xl border border-gray-150 shadow-sm text-gray-400">
          <span class="text-5xl block mb-3">🏷️</span>
          <h3 class="text-lg font-bold text-gray-700">No hay promociones activas</h3>
          <p class="text-sm text-gray-500 max-w-xs mx-auto mt-2">Actualmente no existen promociones vigentes de ningún negocio.</p>
        </div>

      </div>
    </main>
    </div>
  </div>
</template>