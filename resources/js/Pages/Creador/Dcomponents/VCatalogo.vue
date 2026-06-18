<script setup>
import { ref } from 'vue';
import RRcatalogo from './RRcatalogo.vue';
import Edishio from './Edishio.vue';

const props = defineProps({
    catalogo: Object,
    negocio_nombre: String,
    compactMode: {
        type: Boolean,
        default: false
    },
    mostrarEditar: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['editarCatalogo']);

const mostrarModal = ref(false);
const productoSeleccionado = ref(null);

const abrirModalNuevo = () => {
  productoSeleccionado.value = null;
  mostrarModal.value = true;
};

const abrirModalEditar = (producto) => {
  productoSeleccionado.value = producto;
  mostrarModal.value = true;
};
</script>

<template>
    <div class="w-full bg-white border border-gray-200 rounded-xl p-6 my-4 flex flex-col shadow-sm">

        <div class="w-full h-[80px] bg-gray-50 border border-gray-200 rounded-xl px-6 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900 tracking-wide uppercase">{{ catalogo.titulo }}</h1>
            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">{{ negocio_nombre }}</span>
        </div>

        <!-- Grid de productos dinámico -->
        <div :class="['grid gap-6 w-full mt-6', compactMode ? 'grid-cols-1 sm:grid-cols-2 min-[1500px]:grid-cols-3' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4']">

            <div v-for="producto in catalogo.productos" :key="producto.id" class="h-[280px] bg-white border border-gray-200 rounded-xl p-4 flex flex-col justify-between transition-all hover:border-blue-500 hover:shadow-md hover:scale-[1.01] duration-200">
                <div class="w-full h-[170px] bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden relative">
                    <img v-if="producto.imagen" :src="'/storage/' + producto.imagen" class="w-full h-full object-cover" />
                    <span v-else class="text-sm font-medium">🖼️ Sin imagen</span>
                    <span class="absolute top-2 left-2 bg-blue-600 text-xs text-white px-2 py-0.5 rounded font-bold">¡Top!</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h2 class="text-gray-800 font-semibold text-base truncate max-w-[180px]">{{ producto.nombre }}</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-green-600 font-bold text-lg">Bs {{ producto.precio }}</span>
                        <Edishio v-if="mostrarEditar" @click="abrirModalEditar(producto)" />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="mostrarEditar" class="w-full flex justify-center mt-4">
            <button @click="abrirModalNuevo" class="w-full h-[50px] bg-gray-50 hover:bg-blue-50 border-2 border-dashed border-gray-300 hover:border-blue-400 text-gray-500 hover:text-blue-600 rounded-xl flex items-center justify-center gap-2 font-medium transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Agregar nuevo producto al catálogo</span>
            </button>
        </div>

    </div>

    <!-- Componente modal RRcatalogo para añadir o editar -->
    <RRcatalogo 
        v-if="mostrarModal" 
        :catalogo-id="catalogo.id" 
        :producto-a-editar="productoSeleccionado" 
        @cerrar="mostrarModal = false" 
        @guardado="mostrarModal = false" 
    />
</template>
