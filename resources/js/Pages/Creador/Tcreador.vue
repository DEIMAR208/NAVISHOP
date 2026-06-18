<template>
  <div class="fixed inset-0 flex flex-col bg-gray-100 text-gray-800 antialiased overflow-hidden">
    <Header />
    
    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <Menucreador class="hidden md:flex" />

    <main class="flex-1 p-4 sm:p-6 overflow-y-auto w-full">
      <div class="max-w-7xl mx-auto w-full">

        <!-- Formulario de Catálogo (Arriba) -->
        <div v-if="mostrarFormulario" class="w-full bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200 mb-8 animate-fade-in">
          <For
            :negocio_id="negocio_id"
            :catalogos-disponibles="catalogos"
            :edit-mode-initial="modoEdicion"
            @cerrar="cerrarFormulario"
            @guardado="cerrarFormulario"
          />
        </div>

        <!-- Sección de Catálogos (Abajo) -->
        <div class="w-full">
          
          <!-- Caso sin catálogos (solo si el formulario está cerrado) -->
          <div v-if="catalogos.length === 0 && !mostrarFormulario" class="min-h-[60vh] sm:min-h-[70vh] flex items-center justify-center p-2 sm:p-6">
            <div class="max-w-sm w-full bg-white border border-gray-200 border-dashed rounded-2xl p-6 sm:p-8 flex flex-col items-center text-center shadow-sm">
              
              <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 sm:mb-5 animate-pulse">
                <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
              </div>

              <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">¿No hay catálogos aún?</h3>
              <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 font-medium">Comienza a organizar tus productos creando tu primer catálogo personalizado.</p>
              
              <div class="flex flex-col gap-3 w-full">
                <button
                  @click="abrirCrear"
                  class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-5 rounded-xl shadow-md active:scale-95 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 touch-manipulation cursor-pointer font-bold uppercase tracking-wider text-sm"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  <span>Crear Nuevo Catálogo</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Listado de Catálogos (Siempre visible si existen catálogos) -->
          <div v-else-if="catalogos.length > 0" class="w-full">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 px-1">
              <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Mis Catálogos</h1>
                <p class="text-xs sm:text-sm text-gray-500">Gestiona los escaparates virtuales de tu negocio.</p>
              </div>
              
              <div class="flex items-center gap-3 w-full sm:w-auto" v-if="!mostrarFormulario">
                <button
                  @click="abrirEditar"
                  class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm active:scale-95 transition-all duration-150 touch-manipulation text-sm cursor-pointer"
                >
                  ✏️ Editar Catálogo
                </button>
                <button
                  @click="abrirCrear"
                  class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm active:scale-95 transition-all duration-150 touch-manipulation text-sm cursor-pointer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  <span>Nuevo Catálogo</span>
                </button>
              </div>
            </div>

            <div class="flex flex-col gap-8 sm:gap-10 w-full">
              <VCatalogo
                v-for="catalogo in catalogos"
                :key="catalogo.id"
                :catalogo="catalogo"
                :negocio_nombre="negocio_nombre"
                :mostrar-editar="false"
                class="w-full"
              />
            </div>
          </div>

        </div>

      </div>
    </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Menucreador from './Dcomponents/Menucreador.vue';
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue';
import For from './Dcomponents/For.vue';
import VCatalogo from './Dcomponents/VCatalogo.vue';

defineProps({
    negocio_id: Number,
    negocio_nombre: String,
    catalogos: {
        type: Array,
        default: () => []
    }
});

const mostrarFormulario = ref(false);
const modoEdicion = ref(false);

const abrirCrear = () => {
  modoEdicion.value = false;
  mostrarFormulario.value = true;
};

const abrirEditar = () => {
  modoEdicion.value = true;
  mostrarFormulario.value = true;
};

const cerrarFormulario = () => {
  mostrarFormulario.value = false;
  modoEdicion.value = false;
};

onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('edit') === '1') {
    modoEdicion.value = true;
    mostrarFormulario.value = true;
  }
});
</script>