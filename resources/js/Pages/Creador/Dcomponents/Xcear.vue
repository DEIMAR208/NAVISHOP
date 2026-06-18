<template>
  <div class="w-full max-w-4xl mx-auto py-2">
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8">
      <h2 class="text-2xl font-black text-gray-900 mb-6">
        Crear Nueva Promoción
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          
          <!-- COLUMNA IZQUIERDA: Subida de Foto y Vista Previa -->
          <div class="space-y-4">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">
              Foto de la Promoción *
            </label>
            <div class="flex flex-col justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-blue-500 transition-colors bg-gray-50/50">
              <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600 justify-center mt-2">
                <label for="foto" class="relative cursor-pointer bg-white rounded-md font-bold text-blue-600 hover:text-blue-500">
                  <span>Subir archivo</span>
                  <input id="foto" type="file" accept="image/*" @change="handleFileChange" class="sr-only" />
                </label>
                <p class="pl-1 text-gray-500">o arrastra</p>
              </div>
              <p class="text-[10px] text-gray-400 mt-1">PNG, JPG hasta 10MB</p>
            </div>
            
            <!-- Vista previa de la imagen -->
            <div v-if="imagePreview" class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50 max-h-48 flex justify-center items-center">
              <img :src="imagePreview" alt="Vista previa" class="max-h-40 object-contain p-2" />
            </div>
          </div>

          <!-- COLUMNA DERECHA: Campos de Texto -->
          <div class="space-y-4">
            <!-- Título de la promoción -->
            <div class="space-y-1.5">
              <label for="titulo" class="block text-xs font-bold uppercase tracking-wider text-gray-500">
                Título de la Promoción *
              </label>
              <input
                id="titulo"
                v-model="form.titulo"
                type="text"
                required
                placeholder="Ej: 50% de descuento en servicios premium"
                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
              />
            </div>

            <!-- Fecha y hora de inicio -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">
                Fecha y Hora de Inicio *
              </label>
              <div class="grid grid-cols-2 gap-2">
                <input
                  v-model="form.fechaInicio"
                  type="date"
                  required
                  class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                />
                <input
                  v-model="form.horaInicio"
                  type="time"
                  required
                  class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                />
              </div>
            </div>

            <!-- Fecha y hora de finalización -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">
                Fecha y Hora de Finalización *
              </label>
              <div class="grid grid-cols-2 gap-2">
                <input
                  v-model="form.fechaFin"
                  type="date"
                  required
                  class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                />
                <input
                  v-model="form.horaFin"
                  type="time"
                  required
                  class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                />
              </div>
            </div>

            <!-- Descripción -->
            <div class="space-y-1.5">
              <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-gray-500">
                Descripción *
              </label>
              <textarea
                id="descripcion"
                v-model="form.descripcion"
                rows="2"
                required
                placeholder="Detalles de la promoción, términos..."
                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all resize-none"
              ></textarea>
              <p class="text-[10px] text-gray-400 text-right">
                {{ form.descripcion.length }}/500 caracteres
              </p>
            </div>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-100">
          <button
            type="button"
            @click="resetForm"
            class="w-full sm:w-1/2 px-4 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-bold text-sm transition-all active:scale-98 cursor-pointer"
          >
            Cancelar
          </button>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full sm:w-1/2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 text-white font-black rounded-xl text-sm transition-all active:scale-98 cursor-pointer shadow-lg shadow-blue-500/10"
          >
            {{ isSubmitting ? 'Creando...' : 'Crear Promoción' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const isSubmitting = ref(false)
const imagePreview = ref(null)

const form = reactive({
  titulo: '',
  foto: null,
  fechaInicio: '',
  horaInicio: '',
  fechaFin: '',
  horaFin: '',
  descripcion: ''
})

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.foto = file
    // Crear vista previa
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleSubmit = () => {
   isSubmitting.value = true

   const formData = new FormData()
   formData.append('titulo', form.titulo)
   formData.append('foto', form.foto)
   formData.append('fechaInicio', `${form.fechaInicio}T${form.horaInicio}`)
   formData.append('fechaFin', `${form.fechaFin}T${form.horaFin}`)
   formData.append('descripcion', form.descripcion)

   router.post(route('promociones.store'), formData, {
     forceFormData: true,
     onSuccess: () => {
       alert('Promoción creada exitosamente!')
       resetForm()
     },
     onError: (errors) => {
       console.error('Error al crear la promoción:', errors)
       alert('Error al crear la promoción. Intenta de nuevo.')
     },
     onFinish: () => {
       isSubmitting.value = false
     }
   })
 }

const resetForm = () => {
  form.titulo = ''
  form.foto = null
  form.fechaInicio = ''
  form.horaInicio = ''
  form.fechaFin = ''
  form.horaFin = ''
  form.descripcion = ''
  imagePreview.value = null
}
</script>
