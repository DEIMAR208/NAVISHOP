<script setup>
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  catalogoId: {
    type: Number,
    required: true
  },
  productoAEditar: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['cerrar', 'guardado']);

const esEdicion = ref(!!props.productoAEditar);

const form = useForm({
  nombre: props.productoAEditar ? props.productoAEditar.nombre : '',
  precio: props.productoAEditar ? props.productoAEditar.precio : '',
  descripcion: props.productoAEditar ? props.productoAEditar.descripcion : '',
  imagen: null,
  imagenPreview: props.productoAEditar && props.productoAEditar.imagen ? '/storage/' + props.productoAEditar.imagen : null,
  catalogo_id: props.catalogoId,
  producto_id: props.productoAEditar ? props.productoAEditar.id : null
});

// Reaccionar a cambios en el prop si el modal se reutiliza
watch(() => props.productoAEditar, (newVal) => {
  if (newVal) {
    esEdicion.value = true;
    form.nombre = newVal.nombre;
    form.precio = newVal.precio;
    form.descripcion = newVal.descripcion;
    form.imagenPreview = newVal.imagen ? '/storage/' + newVal.imagen : null;
    form.producto_id = newVal.id;
  } else {
    esEdicion.value = false;
    form.reset();
    form.imagenPreview = null;
    form.producto_id = null;
  }
});

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;
  form.imagen = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    form.imagenPreview = e.target.result;
  };
  reader.readAsDataURL(file);
};

const submitFormulario = () => {
  form.processing = true;

  const formData = new FormData();
  formData.append('nombre', form.nombre);
  formData.append('precio', form.precio);
  if (form.descripcion) formData.append('descripcion', form.descripcion);
  formData.append('catalogo_id', form.catalogo_id);

  if (form.imagen instanceof File) {
    formData.append('imagen', form.imagen);
  }

  if (esEdicion.value) {
    router.post(route('producto.update', form.producto_id), formData, {
      forceFormData: true,
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onFinish: () => form.processing = false
    });
  } else {
    router.post(route('producto.store'), formData, {
      forceFormData: true,
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onFinish: () => form.processing = false
    });
  }
};

const borrarProducto = () => {
  if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
    router.delete(route('producto.destroy', form.producto_id), {
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onError: (errors) => {
        console.error('Error al borrar el producto:', errors);
      }
    });
  }
}
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-900">
          {{ esEdicion ? 'Editar Producto' : 'Añadir Nuevo Producto' }}
        </h2>
        <button @click="$emit('cerrar')" class="text-gray-400 hover:text-red-500 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="p-6 space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del producto *</label>
          <input v-model="form.nombre" type="text" placeholder="Ej: Hamburguesa Clásica" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Precio (Bs) *</label>
          <input v-model="form.precio" type="number" step="0.01" placeholder="Ej: 35.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción *</label>
          <textarea v-model="form.descripcion" rows="3" placeholder="Detalles de los ingredientes..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Imagen del producto</label>
          <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors relative h-44 flex items-center justify-center overflow-hidden">
            <input type="file" @change="handleImageUpload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
            <div v-if="!form.imagenPreview" class="pointer-events-none text-gray-500">
              <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              <p class="text-sm">Click para subir foto</p>
            </div>
            <img v-else :src="form.imagenPreview" class="w-full h-full object-cover rounded-md" />
          </div>
        </div>
      </div>

      <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-between items-center rounded-b-2xl">
        <div>
          <button
            v-if="esEdicion"
            @click="borrarProducto"
            type="button"
            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors cursor-pointer"
          >
            Borrar
          </button>
        </div>
        <div class="flex gap-3">
          <button @click="$emit('cerrar')" class="px-5 py-2.5 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">
            Cancelar
          </button>
          <button @click="submitFormulario" :disabled="form.processing" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 flex items-center">
            {{ form.processing ? 'Guardando...' : (esEdicion ? 'Actualizar Producto' : 'Guardar Producto') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
