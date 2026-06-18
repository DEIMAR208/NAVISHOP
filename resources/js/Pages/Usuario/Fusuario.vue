<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// --- ESTADO DEL FORMULARIO ---
const nombre = ref('')
const tipoNegocio = ref('')
const tieneLogo = ref(null)
const tieneNit = ref(null)
const nitValue = ref('')

// --- ARCHIVOS ---
const fotoFachadaArchivo = ref(null)
const logoArchivo = ref(null)

// Datos de Contacto
const telefono = ref('')
const correo = ref('')

// Ubicación
const direccion = ref('')
const googleMaps = ref('')

// Horarios
const horaApertura = ref('')
const horaCierre = ref('')
const diasTrabajo = ref([])

// Redes Sociales
const facebook = ref('')
const instagram = ref('')
const tiktok = ref('')

// --- DATOS PREDEFINIDOS ---
const categorias = [
  'Restaurante / Comida',
  'Tienda de Ropa / Moda',
  'Tecnología / Celulares',
  'Farmacia / Salud',
  'Supermercado / Plaza',
  'Servicios Técnicos',
  'Otro'
]

const diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']

// --- MANEJADORES ---
const handleFotoNegocio = (event) => {
  fotoFachadaArchivo.value = event.target.files[0]
}

const handleLogo = (event) => {
  logoArchivo.value = event.target.files[0]
}

const enviarFormulario = () => {
  const formData = new FormData()
  formData.append('nombre', nombre.value)
  formData.append('tipo', tipoNegocio.value)
  formData.append('telefono', telefono.value)
  formData.append('correo', correo.value)
  formData.append('direccion', direccion.value)
  formData.append('google_maps', googleMaps.value || '')
  formData.append('hora_apertura', horaApertura.value)
  formData.append('hora_cierre', horaCierre.value)
  formData.append('dias_trabajo', JSON.stringify(diasTrabajo.value))
  formData.append('facebook', facebook.value || '')
  formData.append('instagram', instagram.value || '')
  formData.append('tiktok', tiktok.value || '')

  if (fotoFachadaArchivo.value) {
    formData.append('foto_fachada', fotoFachadaArchivo.value)
  }

  if (tieneLogo.value === 'si' && logoArchivo.value) {
    formData.append('logo', logoArchivo.value)
  }

  if (tieneNit.value === 'si') {
    formData.append('nit', nitValue.value)
  }

  router.post(route('negocios.store'), formData, {
    forceFormData: true,
    onSuccess: (response) => {
      if (response?.props?.redirect) {
        window.location.href = response.props.redirect
      }
    },
    onError: (errors) => {
      console.error(errors)
      alert('Error al guardar')
    }
  })
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 md:p-10">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

      <div class="bg-indigo-600 p-6 text-white text-center">
        <h2 class="text-xl sm:text-2xl font-bold tracking-tight">Registrar Nuevo Negocio</h2>
        <p class="text-indigo-100 text-sm mt-1">Completa el perfil comercial de tu establecimiento</p>
      </div>

      <form @submit.prevent="enviarFormulario" class="p-6 space-y-6">

        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">1. Datos del Negocio</h3>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre del Negocio</label>
            <input v-model="nombre" type="text" placeholder="Ej. Cafetería Central" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all text-sm outline-none" required />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipo de Negocio</label>
            <select v-model="tipoNegocio" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all text-sm outline-none" required>
              <option value="" disabled selected>Selecciona una categoría</option>
              <option v-for="cat in categorias" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto del Local (Fachada)</label>
            <input @change="handleFotoNegocio" type="file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-dashed border-slate-300 p-2 rounded-xl bg-slate-50" required />
          </div>
        </div>

        <hr class="border-slate-200">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="space-y-3">
            <label class="block text-sm font-semibold text-slate-700">¿Tiene Logo?</label>
            <div class="flex gap-2">
              <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 flex-1 justify-center active:scale-95 transition-transform"><input type="radio" v-model="tieneLogo" value="si" class="text-indigo-600"><span class="text-sm font-medium text-slate-700">Sí</span></label>
              <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 flex-1 justify-center active:scale-95 transition-transform"><input type="radio" v-model="tieneLogo" value="no" class="text-indigo-600"><span class="text-sm font-medium text-slate-700">No</span></label>
            </div>
            <div v-if="tieneLogo === 'si'" class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-1.5">
              <label class="block text-xs font-bold text-indigo-800 uppercase">Subir Logo</label>
              <input @change="handleLogo" type="file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:bg-white file:text-indigo-700 border border-indigo-200 p-1 rounded bg-white" required />
            </div>
            <div v-if="tieneLogo === 'no'" class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-3">
              <img src="/iconos/defecto.png" alt="Logo por defecto" class="w-12 h-12 rounded-xl object-cover border border-slate-200" />
              <div>
                <p class="text-xs font-bold text-slate-700">Logo por defecto</p>
                <p class="text-[11px] text-slate-500">Se usará la imagen por defecto para tu negocio.</p>
              </div>
            </div>
          </div>

          <div class="space-y-3">
            <label class="block text-sm font-semibold text-slate-700">¿Cuenta con NIT?</label>
            <div class="flex gap-2">
              <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 flex-1 justify-center active:scale-95 transition-transform"><input type="radio" v-model="tieneNit" value="si" class="text-indigo-600"><span class="text-sm font-medium text-slate-700">Sí</span></label>
              <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 flex-1 justify-center active:scale-95 transition-transform"><input type="radio" v-model="tieneNit" value="no" class="text-indigo-600"><span class="text-sm font-medium text-slate-700">No</span></label>
            </div>
            <div v-if="tieneNit === 'si'" class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-1.5">
              <label class="block text-xs font-bold text-indigo-800 uppercase">Número de NIT</label>
              <input v-model="nitValue" type="number" placeholder="Ej. 10203040" class="w-full px-3 py-1.5 bg-white border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required />
            </div>
          </div>
        </div>

        <hr class="border-slate-200">

        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">2. Datos de Contacto</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono / WhatsApp</label>
              <input v-model="telefono" type="tel" placeholder="Ej. +591 71234567" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" required />
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Correo Electrónico</label>
              <input v-model="correo" type="email" placeholder="ejemplo@negocio.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" required />
            </div>
          </div>
        </div>

        <hr class="border-slate-200">

        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">3. Ubicación</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dirección Física</label>
              <input v-model="direccion" type="text" placeholder="Calle, Nro, Zona o Barrio" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" required />
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Enlace de Google Maps <span class="text-xs font-normal text-slate-400">(Opcional)</span></label>
              <input v-model="googleMaps" type="url" placeholder="https://maps.google.com/..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" />
            </div>
          </div>
        </div>

        <hr class="border-slate-200">

        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">4. Horarios de Atención</h3>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Días Laborales</label>
            <div class="flex flex-wrap gap-2">
              <label
                v-for="dia in diasSemana"
                :key="dia"
                :class="[
                  'flex-1 min-w-[60px] text-center py-2 rounded-xl text-xs font-bold border cursor-pointer select-none transition-all active:scale-95',
                  diasTrabajo.includes(dia)
                    ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100'
                    : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'
                ]"
              >
                <input type="checkbox" :value="dia" v-model="diasTrabajo" class="hidden" />
                {{ dia }}
              </label>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hora Apertura</label>
              <input v-model="horaApertura" type="time" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" required />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hora Cierre</label>
              <input v-model="horaCierre" type="time" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm outline-none" required />
            </div>
          </div>
        </div>

        <hr class="border-slate-200">

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">5. Redes Sociales</h3>
            <span class="text-xs text-slate-400 font-medium">Opcionales</span>
          </div>

          <div class="space-y-3">
            <div class="flex rounded-xl shadow-sm bg-slate-50 border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:bg-white transition-all">
              <span class="inline-flex items-center px-3 bg-slate-200 text-slate-600 text-xs font-bold border-r border-slate-300 select-none w-24 justify-center">Facebook</span>
              <input v-model="facebook" type="url" placeholder="https://facebook.com/tu-pagina" class="w-full px-3 py-2 bg-transparent text-sm outline-none" />
            </div>

            <div class="flex rounded-xl shadow-sm bg-slate-50 border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:bg-white transition-all">
              <span class="inline-flex items-center px-3 bg-slate-200 text-slate-600 text-xs font-bold border-r border-slate-300 select-none w-24 justify-center">Instagram</span>
              <input v-model="instagram" type="url" placeholder="https://instagram.com/tu-usuario" class="w-full px-3 py-2 bg-transparent text-sm outline-none" />
            </div>

            <div class="flex rounded-xl shadow-sm bg-slate-50 border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:bg-white transition-all">
              <span class="inline-flex items-center px-3 bg-slate-200 text-slate-600 text-xs font-bold border-r border-slate-300 select-none w-24 justify-center">TikTok</span>
              <input v-model="tiktok" type="url" placeholder="https://tiktok.com/@tu-cuenta" class="w-full px-3 py-2 bg-transparent text-sm outline-none" />
            </div>
          </div>
        </div>

        <div class="pt-6">
          <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:bg-indigo-700 active:scale-[0.99] transition-all text-sm sm:text-base tracking-wide">
            Finalizar Registro de Negocio
          </button>
        </div>

      </form>
    </div>
  </div>
</template>
