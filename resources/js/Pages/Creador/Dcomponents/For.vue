<template>
  <div class="py-2 px-4 sm:px-6 lg:px-8 bg-gray-50 h-auto text-gray-800">
    <div class="max-w-4xl mx-auto">

      <!-- Encabezado del Formulario -->
      <div class="flex justify-between items-center mb-8 bg-white border border-gray-200 p-6 rounded-xl shadow-sm">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 tracking-wide">
            {{ isEditMode ? 'Editar Catálogo' : 'Crear Nuevo Catálogo' }}
          </h1>
          <p class="text-xs text-gray-500 mt-1">
            {{ isEditMode ? 'Selecciona uno de tus catálogos para modificarlo o eliminarlo' : 'Organiza tus productos y lánzalos directo a la plataforma' }}
          </p>
        </div>
        <button @click="$emit('cerrar')" type="button" class="p-2 text-gray-400 hover:text-red-500 hover:bg-gray-100 rounded-lg transition-all duration-200 cursor-pointer">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Selector de Catálogo a Editar (sólo en modo edición) -->
      <div v-if="isEditMode" class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <label class="block text-sm font-semibold text-gray-700 mb-3 tracking-wide uppercase">Selecciona el Catálogo a Editar <span class="text-red-500">*</span></label>
        <select
          v-model="selectedCatalogId"
          @change="cargarCatalogoAEditar"
          class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all outline-none cursor-pointer font-medium"
        >
          <option value="" disabled>-- Elige un catálogo de la lista --</option>
          <option v-for="cat in catalogosDisponibles" :key="cat.id" :value="cat.id">
            {{ cat.titulo }}
          </option>
        </select>
      </div>

      <!-- Input de Título -->
      <div v-if="!isEditMode || selectedCatalogId" class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <label class="block text-sm font-semibold text-gray-700 mb-3 tracking-wide uppercase">Título o Nombre del Catálogo <span class="text-red-500">*</span></label>
        <input
          v-model="form.titulo"
          type="text"
          placeholder="Ej: Menú de Hamburguesas - Fin de Semana"
          class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all font-medium"
        />
      </div>

      <!-- Botón de Nuevo Producto -->
      <div v-if="!isEditMode || selectedCatalogId" class="mb-6">
        <button
          @click="agregarProducto"
          type="button"
          class="w-full flex items-center justify-center px-4 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-sm cursor-pointer"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          NUEVO PRODUCTO
        </button>
      </div>

      <!-- Listado de Productos -->
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6" v-if="form.productos.length > 0 && (!isEditMode || selectedCatalogId)">
        <div v-for="(producto, index) in form.productos" :key="index" class="px-6 py-6 border-b border-gray-200 last:border-b-0 bg-gray-50/50">

          <div class="flex items-center justify-between mb-4 bg-white p-3 rounded-lg border border-gray-200">
            <div class="flex items-center space-x-2">
              <h4 class="font-extrabold text-base">
                <span v-if="form.titulo" class="text-blue-600 font-black text-2xl">para "{{ form.titulo }}"</span>
                <span v-else class="text-gray-800 font-bold text-sm">Producto #{{ index + 1 }}</span>
              </h4>
            </div>
            <button @click="eliminarProducto(index)" type="button" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-gray-100 rounded-md transition-colors cursor-pointer">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Subida de Imagen -->
            <div>
              <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Foto del producto *</label>
              <div class="relative border-2 border-dashed border-gray-300 bg-white rounded-xl h-[190px] flex flex-col items-center justify-center text-center hover:border-blue-600 transition-colors cursor-pointer group overflow-hidden">
                <input
                  type="file"
                  accept="image/*"
                  @change="(e) => handleProductImage(e, index)"
                  class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                />
                <div v-if="!producto.imagenPreview" class="p-4 pointer-events-none">
                   <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-blue-600 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                   </svg>
                   <p class="text-xs font-medium text-gray-500 group-hover:text-gray-900 transition-colors">Seleccionar Imagen</p>
                   <p class="text-[10px] text-gray-400 mt-1">PNG, JPG hasta 5MB</p>
                </div>
                <img v-else :src="producto.imagenPreview" alt="Producto" class="w-full h-full object-cover rounded-xl transition-transform duration-300 group-hover:scale-105" />
              </div>
            </div>

            <!-- Campos del Producto -->
            <div class="md:col-span-2 space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                  <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Nombre del producto *</label>
                  <input
                    v-model="producto.nombre"
                    type="text"
                    placeholder="Ej: Whopper Doble con Queso"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all font-medium"
                  />
                </div>
                <div>
                  <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Precio (Bs) *</label>
                  <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-400 font-medium">Bs</span>
                    <input
                      v-model="producto.precio"
                      type="number"
                      step="0.01"
                      placeholder="0.00"
                      class="w-full pl-9 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition-all font-medium"
                    />
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Descripción del Producto *</label>
                <textarea
                  v-model="producto.descripcion"
                  rows="3"
                  placeholder="Detalla los ingredientes o tamaño (Ej: Carne 100% de res a la parrilla, queso cheddar, pepinillos...)"
                  class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all resize-none text-sm font-medium"
                ></textarea>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Botones de Acción Footer (Cerrar, Borrar, Guardar) -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
        <button
          @click="$emit('cerrar')"
          type="button"
          class="w-full sm:w-auto px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-all text-xs uppercase tracking-wider shadow-sm cursor-pointer active:scale-95"
        >
          Cerrar
        </button>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end" v-if="!isEditMode || selectedCatalogId">
          <button
            v-if="isEditMode && selectedCatalogId"
            @click="borrarCatalogo"
            type="button"
            class="w-full sm:w-auto px-6 py-2.5 bg-red-650 hover:bg-red-700 text-white font-bold rounded-lg transition-all text-xs uppercase tracking-wider shadow-sm cursor-pointer active:scale-95"
          >
            Borrar
          </button>

          <button
            @click="guardarCatalogo"
            type="button"
            :disabled="isSubmitting"
            class="w-full sm:w-auto px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all text-xs uppercase tracking-wider shadow-sm disabled:opacity-50 cursor-pointer active:scale-95"
          >
            {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  negocio_id: Number,
  catalogosDisponibles: {
    type: Array,
    default: () => []
  },
  editModeInitial: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['guardado', 'cerrar'])

const isSubmitting = ref(false)
const isEditMode = ref(props.editModeInitial)
const selectedCatalogId = ref('')

const form = ref({
  titulo: '',
  productos: [],
})

watch(() => props.editModeInitial, (newVal) => {
  isEditMode.value = newVal
  if (!newVal) {
    selectedCatalogId.value = ''
    form.value.titulo = ''
    form.value.productos = []
  }
})

const cargarCatalogoAEditar = () => {
  const selectedCat = props.catalogosDisponibles.find(c => c.id === selectedCatalogId.value);
  if (selectedCat) {
    form.value.titulo = selectedCat.titulo;
    form.value.productos = selectedCat.productos.map(p => ({
      id: p.id,
      nombre: p.nombre,
      precio: p.precio,
      descripcion: p.descripcion || '',
      imagen: null,
      imagenPreview: p.imagen ? '/storage/' + p.imagen : null
    }));
  } else {
    form.value.titulo = '';
    form.value.productos = [];
  }
}

const agregarProducto = () => {
  form.value.productos.unshift({
    id: null,
    nombre: '',
    precio: '',
    descripcion: '',
    imagen: null,
    imagenPreview: null
  })
}

const eliminarProducto = (index) => {
  form.value.productos.splice(index, 1)
}

const handleProductImage = (event, index) => {
  const file = event.target.files[0]
  if (!file) return

  form.value.productos[index].imagen = file

  const reader = new FileReader()
  reader.onload = (e) => {
    form.value.productos[index].imagenPreview = e.target.result
  }
  reader.readAsDataURL(file)
}

const guardarCatalogo = () => {
  if (isEditMode.value && !selectedCatalogId.value) {
    alert('Por favor selecciona un catálogo para editar.');
    return;
  }

  isSubmitting.value = true

  const formData = new FormData()
  formData.append('titulo', form.value.titulo)
  formData.append('negocio_id', props.negocio_id)

  form.value.productos.forEach((producto, index) => {
    if (producto.id) {
      formData.append(`productos[${index}][id]`, producto.id)
    }
    formData.append(`productos[${index}][nombre]`, producto.nombre || '')
    formData.append(`productos[${index}][precio]`, producto.precio || '')
    formData.append(`productos[${index}][descripcion]`, producto.descripcion || '')
    if (producto.imagen) {
      formData.append(`productos[${index}][imagen]`, producto.imagen)
    }
  })

  // Determinar si es actualización o creación
  const url = isEditMode.value 
    ? route('catalogo.update', selectedCatalogId.value) 
    : route('catalogo.store');

  router.post(url, formData, {
    forceFormData: true,
    onSuccess: () => {
      form.value.titulo = ''
      form.value.productos = []
      selectedCatalogId.value = ''
      emit('guardado')
    },
    onError: (errors) => {
      console.error('Error al guardar el catálogo:', errors)
      alert('Error al guardar el catálogo. Intenta de nuevo.')
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const borrarCatalogo = () => {
  if (!selectedCatalogId.value) return;
  
  if (confirm('¿Estás seguro de que deseas eliminar este catálogo por completo?')) {
    router.delete(route('catalogo.destroy', selectedCatalogId.value), {
      onSuccess: () => {
        selectedCatalogId.value = ''
        form.value.titulo = ''
        form.value.productos = []
        emit('guardado')
        emit('cerrar')
      },
      onError: (errors) => {
        console.error('Error al eliminar el catálogo:', errors)
      }
    });
  }
}
</script>
