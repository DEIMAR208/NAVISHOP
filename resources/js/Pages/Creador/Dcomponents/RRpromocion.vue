<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  promocionAEditar: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['cerrar', 'guardado']);

const esEdicion = ref(!!props.promocionAEditar);

const form = useForm({
  titulo: '',
  descripcion: '',
  fechaInicio: '',
  horaInicio: '',
  fechaFin: '',
  horaFin: '',
  foto: null,
  fotoPreview: null
});

const splitDateTime = (dateTimeStr) => {
  if (!dateTimeStr) return { date: '', time: '' };
  
  // Si viene como string
  const str = typeof dateTimeStr === 'string' 
    ? dateTimeStr 
    : new Date(dateTimeStr).toISOString();
    
  const parts = str.split(/[T ]/);
  const date = parts[0] || '';
  let time = parts[1] || '';
  if (time) {
    time = time.substring(0, 5); // Mantener solo HH:MM
  }
  return { date, time };
};

const cargarDatosFormulario = (promo) => {
  if (promo) {
    esEdicion.value = true;
    const inicio = splitDateTime(promo.fecha_inicio);
    const fin = splitDateTime(promo.fecha_fin);
    
    form.titulo = promo.titulo || '';
    form.descripcion = promo.descripcion || '';
    form.fechaInicio = inicio.date;
    form.horaInicio = inicio.time;
    form.fechaFin = fin.date;
    form.horaFin = fin.time;
    form.foto = null;
    form.fotoPreview = promo.foto ? '/storage/' + promo.foto : null;
  } else {
    esEdicion.value = false;
    form.titulo = '';
    form.descripcion = '';
    form.fechaInicio = '';
    form.horaInicio = '';
    form.fechaFin = '';
    form.horaFin = '';
    form.foto = null;
    form.fotoPreview = null;
  }
};

// Cargar inicialmente
onMounted(() => {
  cargarDatosFormulario(props.promocionAEditar);
});

// Escuchar cambios por si se reabre o cambia el prop
watch(() => props.promocionAEditar, (newVal) => {
  cargarDatosFormulario(newVal);
}, { deep: true });

const handleFotoUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;
  form.foto = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    form.fotoPreview = e.target.result;
  };
  reader.readAsDataURL(file);
};

const submitFormulario = () => {
  form.processing = true;

  const formData = new FormData();
  formData.append('titulo', form.titulo);
  formData.append('descripcion', form.descripcion);
  formData.append('fechaInicio', `${form.fechaInicio}T${form.horaInicio}`);
  formData.append('fechaFin', `${form.fechaFin}T${form.horaFin}`);

  if (form.foto instanceof File) {
    formData.append('foto', form.foto);
  }

  if (esEdicion.value) {
    router.post(route('promociones.update', props.promocionAEditar.id), formData, {
      forceFormData: true,
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onFinish: () => form.processing = false
    });
  } else {
    // Si queremos usarlo para crear en el futuro
    router.post(route('promociones.store'), formData, {
      forceFormData: true,
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onFinish: () => form.processing = false
    });
  }
};

const borrarPromocion = () => {
  if (confirm('¿Estás seguro de que deseas eliminar esta promoción?')) {
    router.delete(route('promociones.destroy', props.promocionAEditar.id), {
      onSuccess: () => {
        emit('guardado');
        emit('cerrar');
      },
      onError: (errors) => {
        console.error('Error al borrar la promoción:', errors);
      }
    });
  }
}
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
      <!-- Cabecera -->
      <div class="flex justify-between items-center p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-900">
          {{ esEdicion ? 'Editar Promoción' : 'Crear Nueva Promoción' }}
        </h2>
        <button @click="$emit('cerrar')" class="text-gray-400 hover:text-red-500 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Cuerpo del Formulario -->
      <div class="p-6 space-y-4">
        <!-- Título -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Título de la Promoción *</label>
          <input 
            v-model="form.titulo" 
            type="text" 
            placeholder="Ej: 50% de descuento en el segundo plato" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
            required
          />
        </div>

        <!-- Foto de la promoción -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Foto de la Promoción</label>
          <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors relative h-44 flex items-center justify-center overflow-hidden">
            <input 
              type="file" 
              @change="handleFotoUpload" 
              accept="image/*" 
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
            />
            <div v-if="!form.fotoPreview" class="pointer-events-none text-gray-500">
              <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              <p class="text-sm">Click para subir o arrastrar foto</p>
              <p class="text-xs text-gray-400 mt-1">PNG, JPG hasta 2MB</p>
            </div>
            <img v-else :src="form.fotoPreview" class="w-full h-full object-contain rounded-md" />
          </div>
        </div>

        <!-- Fecha y hora de inicio -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha y Hora de Inicio *</label>
          <div class="grid grid-cols-2 gap-4">
            <input 
              v-model="form.fechaInicio" 
              type="date" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
              required
            />
            <input 
              v-model="form.horaInicio" 
              type="time" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
              required
            />
          </div>
        </div>

        <!-- Fecha y hora de fin -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha y Hora de Fin *</label>
          <div class="grid grid-cols-2 gap-4">
            <input 
              v-model="form.fechaFin" 
              type="date" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
              required
            />
            <input 
              v-model="form.horaFin" 
              type="time" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
              required
            />
          </div>
        </div>

        <!-- Descripción -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción *</label>
          <textarea 
            v-model="form.descripcion" 
            rows="3" 
            placeholder="Detalles sobre los términos de la promoción, validez, etc." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
            required
          ></textarea>
        </div>
      </div>

      <!-- Acciones de pie de página -->
      <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-between items-center rounded-b-2xl">
        <div>
          <button
            v-if="esEdicion"
            @click="borrarPromocion"
            type="button"
            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors cursor-pointer"
          >
            Borrar
          </button>
        </div>
        <div class="flex gap-3">
          <button
            @click="$emit('cerrar')"
            class="px-5 py-2.5 text-gray-650 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors"
          >
            Cancelar
          </button>
          <button
            @click="submitFormulario"
            :disabled="form.processing"
            class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 flex items-center"
          >
            {{ form.processing ? 'Guardando...' : (esEdicion ? 'Actualizar Promoción' : 'Crear Promoción') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
