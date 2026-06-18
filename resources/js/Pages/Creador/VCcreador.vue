<template>
  <Head title="Crear Video Creador" />

  <div class="fixed inset-0 flex flex-col bg-gray-50 text-gray-800 font-sans overflow-hidden">
    <Header />

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <!-- Sidebar del Creador -->
      <Menucreador class="hidden md:flex" />

      <!-- Área de Contenido Principal -->
      <main class="flex-1 p-6 bg-gray-50 text-gray-800 overflow-y-auto">
        <div class="max-w-7xl w-full mx-auto flex flex-col lg:flex-row justify-center items-start gap-8 py-4">
          
          <!-- Mockup del Teléfono (Previsualización) -->
          <div class="relative w-full max-w-[320px] aspect-[9/16] bg-gradient-to-b from-slate-800 to-slate-950 rounded-3xl overflow-hidden shadow-2xl border-4 border-gray-900 flex flex-col justify-between p-4 min-h-[500px]">
            

            <!-- Contenedor del Iframe de Video -->
            <div class="relative flex-1 w-full my-3 rounded-2xl overflow-hidden border-2 border-yellow-500/50 bg-black shadow-[0_0_20px_rgba(0,0,0,0.8)] z-10">
              <iframe 
                v-if="embedUrl"
                :src="embedUrl" 
                class="w-full h-full"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
              </iframe>
              <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-4 text-center">
                <span class="text-4xl mb-2">📺</span>
                <p class="text-sm font-bold">Introduce un enlace válido a la derecha para reproducir el video.</p>
              </div>
            </div>

            <!-- Información del Canal/Creador y Detalle de Video -->
            <div class="relative z-10 bg-black/70 backdrop-blur-md rounded-2xl p-3 border border-white/10 space-y-2 text-xs">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <img 
                    :src="businessLogo" 
                    alt="Logo" 
                    class="w-8 h-8 rounded-full border border-white overflow-hidden object-cover"
                  />
                  <span class="font-bold tracking-tight text-gray-200 truncate max-w-[150px]">{{ businessHandle }}</span>
                </div>
                <button class="bg-sky-500 hover:bg-sky-600 text-white font-black px-4 py-1.5 rounded-xl text-xs transition-colors shadow-md">
                  Unirse
                </button>
              </div>

              <div class="space-y-1 font-medium text-gray-300">
                <p class="flex items-center gap-1.5 truncate">
                  <span class="text-base">▶️</span> 
                  <span class="text-white font-bold">{{ form.titulo || 'Previsualización del video actual...' }}</span>
                </p>
                <p class="flex items-center gap-1.5 text-red-400 font-bold text-[11px] line-clamp-2">
                  <span>⛔</span> {{ form.descripcion || 'Sin descripción o advertencia...' }}
                </p>
              </div>
            </div>

          </div>

          <!-- Formulario/Controlador de Creación de Video -->
          <div class="w-full max-w-2xl bg-white border border-gray-200 rounded-3xl p-6 space-y-5 shadow-sm">
            <div>
              <h3 class="text-xl font-black text-gray-900 mb-1">Registrar Nuevo Video</h3>
              <p class="text-xs text-gray-500">Completa la información del video. Pega un enlace de YouTube o TikTok para previsualizarlo en el mockup.</p>
            </div>

            <!-- Errores de Laravel -->
            <div v-if="Object.keys(form.errors).length > 0" class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
              <ul class="list-disc pl-4 space-y-0.5">
                <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
              </ul>
            </div>

            <!-- Form fields -->
            <form @submit.prevent="subirVideo" class="space-y-4">
              
              <!-- Fila 1: Negocio y Título -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Negocio -->
                <div class="space-y-1.5">
                  <label class="text-xs font-bold uppercase tracking-wider text-gray-500">Asociar al Negocio *</label>
                  <select 
                    v-model="form.negocio_id" 
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                    required
                  >
                    <option value="" disabled>Selecciona tu negocio</option>
                    <option v-for="negocio in misNegocios" :key="negocio.id" :value="negocio.id">
                      {{ negocio.nombre }}
                    </option>
                  </select>
                </div>

                <!-- Título -->
                <div class="space-y-1.5">
                  <label class="text-xs font-bold uppercase tracking-wider text-gray-500">Título del Video *</label>
                  <input 
                    v-model="form.titulo" 
                    type="text" 
                    placeholder="Ej. Conoce nuestro local" 
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                    required
                  />
                </div>
              </div>

              <!-- Fila 2: Enlace del Video y Promoción -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Enlace de Video -->
                <div class="space-y-1.5">
                  <label class="text-xs font-bold uppercase tracking-wider text-gray-500">Enlace del Video (YouTube o TikTok) *</label>
                  <input 
                    v-model="form.link_video" 
                    type="text" 
                    placeholder="Enlace de YouTube o TikTok..." 
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                    required
                  />
                  <!-- Demos rápidas en formato compacto (ahorra mucho espacio) -->
                  <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Demos:</span>
                    <button 
                      v-for="(demo, index) in demoVideos" 
                      :key="index"
                      type="button"
                      @click="loadDemo(demo)"
                      class="bg-gray-100 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 rounded-lg px-2 py-0.5 text-[10px] font-semibold transition-colors"
                    >
                      {{ index === 0 ? 'YouTube Shorts' : 'TikTok' }}
                    </button>
                  </div>
                </div>

                <!-- ¿Es una promoción? -->
                <div class="space-y-1.5 flex flex-col justify-between">
                  <label class="text-xs font-bold uppercase tracking-wider text-gray-500 block">¿Este video es una promoción?</label>
                  <div class="flex bg-gray-100 p-1 rounded-2xl border border-gray-200 w-full max-w-[200px]">
                    <button 
                      type="button"
                      @click="form.es_promocion = true"
                      :class="form.es_promocion 
                        ? 'bg-blue-600 text-white shadow-sm' 
                        : 'text-gray-500 hover:text-gray-700'"
                      class="flex-1 text-center py-1 text-xs font-bold rounded-xl transition-all duration-200"
                    >
                      Sí
                    </button>
                    <button 
                      type="button"
                      @click="form.es_promocion = false"
                      :class="!form.es_promocion 
                        ? 'bg-white text-gray-800 shadow-sm border border-gray-200/50' 
                        : 'text-gray-500 hover:text-gray-700'"
                      class="flex-1 text-center py-1 text-xs font-bold rounded-xl transition-all duration-200"
                    >
                      No
                    </button>
                  </div>
                </div>
              </div>

              <!-- Descripción -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-gray-500">Descripción (Opcional)</label>
                <textarea 
                  v-model="form.descripcion" 
                  rows="2" 
                  placeholder="Breve descripción de este video..." 
                  class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all resize-none"
                ></textarea>
              </div>

              <!-- Fechas de la Promoción -->
              <div v-if="form.es_promocion" class="space-y-4 border-l-2 border-blue-500 pl-4 py-1 animate-fadeIn">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Fecha y Hora de Inicio -->
                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Fecha y Hora de Inicio *</label>
                    <div class="grid grid-cols-2 gap-2">
                      <input 
                        v-model="form.fechaInicio" 
                        type="date" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                        :required="form.es_promocion"
                      />
                      <input 
                        v-model="form.horaInicio" 
                        type="time" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                        :required="form.es_promocion"
                      />
                    </div>
                  </div>

                  <!-- Fecha y Hora de Fin -->
                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Fecha y Hora de Fin *</label>
                    <div class="grid grid-cols-2 gap-2">
                      <input 
                        v-model="form.fechaFin" 
                        type="date" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                        :required="form.es_promocion"
                      />
                      <input 
                        v-model="form.horaFin" 
                        type="time" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-800 outline-none transition-all"
                        :required="form.es_promocion"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <button 
                type="submit" 
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 text-white font-black py-3 rounded-xl text-sm transition-all uppercase tracking-wider shadow-lg shadow-blue-500/20 active:scale-98 cursor-pointer animate-pulse"
              >
                {{ form.processing ? 'Guardando...' : 'Publicar Video' }}
              </button>
            </form>
          </div>

        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import Menucreador from './Dcomponents/Menucreador.vue';
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue';

const props = defineProps({
  misNegocios: Array
});

// Enlace por defecto (inicial)
const form = useForm({
  titulo: '¿VALE la PENA la FLECHA MONOLÍTICA?',
  descripcion: 'NO ELIJAS MAL tu HABILIDAD ÉPICA GRATIS ...',
  link_video: 'https://www.youtube.com/shorts/WM8b8XfS8Z0',
  negocio_id: '',
  es_promocion: false,
  fechaInicio: '',
  horaInicio: '',
  fechaFin: '',
  horaFin: '',
  fecha_inicio: null,
  fecha_fin: null
});

onMounted(() => {
  if (props.misNegocios && props.misNegocios.length > 0) {
    form.negocio_id = props.misNegocios[0].id;
  }
});

// Lista de ejemplos interactivos
const demoVideos = [
  {
    title: '¿VALE la PENA la FLECHA MONOLÍTICA? (YouTube)',
    url: 'https://www.youtube.com/shorts/WM8b8XfS8Z0'
  },
  {
    title: 'Demo de Clash of Clans (TikTok)',
    url: 'https://www.tiktok.com/@clashofclans/video/7258593859359325959'
  }
];

// Computar información del negocio seleccionado
const selectedBusiness = computed(() => {
  if (!props.misNegocios) return null;
  return props.misNegocios.find(n => n.id === form.negocio_id);
});

const selectedBusinessName = computed(() => {
  return selectedBusiness.value ? `CÓDIGO: ${selectedBusiness.value.nombre.toUpperCase()}` : 'CÓDIGO: GONCA';
});

const businessInitials = computed(() => {
  return selectedBusiness.value ? selectedBusiness.value.nombre.charAt(0).toUpperCase() : 'C';
});

const businessHandle = computed(() => {
  return selectedBusiness.value ? `@${selectedBusiness.value.nombre.toLowerCase().replace(/\s+/g, '')}` : '@goncaclashofclans';
});

const businessLogo = computed(() => {
  return selectedBusiness.value && selectedBusiness.value.logo 
    ? `/storage/${selectedBusiness.value.logo}` 
    : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80';
});

// Helper para convertir URL de YouTube o TikTok a formato embed de forma robusta
const getEmbedUrl = (url) => {
  if (!url) return '';
  const trimmed = url.trim();

  // 1. Detección de YouTube
  let ytId = null;
  if (trimmed.includes('youtu.be/')) {
    const parts = trimmed.split('youtu.be/');
    if (parts[1]) ytId = parts[1].substring(0, 11);
  } else if (trimmed.includes('/shorts/')) {
    const parts = trimmed.split('/shorts/');
    if (parts[1]) ytId = parts[1].substring(0, 11);
  } else if (trimmed.includes('v=')) {
    const parts = trimmed.split('v=');
    if (parts[1]) ytId = parts[1].substring(0, 11);
  } else if (trimmed.includes('/embed/')) {
    const parts = trimmed.split('/embed/');
    if (parts[1]) ytId = parts[1].substring(0, 11);
  }

  if (ytId && ytId.length === 11) {
    return `https://www.youtube.com/embed/${ytId}?autoplay=1&loop=1&playlist=${ytId}`;
  }

  // 2. Detección de TikTok
  if (trimmed.includes('tiktok.com')) {
    const ttMatch = trimmed.match(/video\/(\d+)/) || trimmed.match(/\b\d{15,22}\b/);
    const ttId = ttMatch ? ttMatch[1] || ttMatch[0] : null;
    if (ttId) {
      return `https://www.tiktok.com/player/v1/${ttId}?music_info=0&description=0&controls=1&autoplay=1`;
    }
  }

  return '';
};

// Computar la URL de inserción (Embed) para que YouTube o TikTok permita reproducirlo dentro del iframe
const embedUrl = computed(() => {
  return getEmbedUrl(form.link_video);
});

// Guardar el video
const subirVideo = () => {
  if (form.es_promocion) {
    if (!form.fechaInicio || !form.horaInicio || !form.fechaFin || !form.horaFin) {
      alert('Por favor complete las fechas y horas de inicio y fin para la promoción.');
      return;
    }
    form.fecha_inicio = `${form.fechaInicio} ${form.horaInicio}:00`;
    form.fecha_fin = `${form.fechaFin} ${form.horaFin}:00`;
  } else {
    form.fecha_inicio = null;
    form.fecha_fin = null;
  }

  form.post(route('creador.videos.store'), {
    onSuccess: () => {
      form.reset('titulo', 'descripcion', 'link_video', 'es_promocion', 'fechaInicio', 'horaInicio', 'fechaFin', 'horaFin', 'fecha_inicio', 'fecha_fin');
      alert('¡Video publicado exitosamente!');
    }
  });
};

// Cargar desde la lista de ejemplos
const loadDemo = (demo) => {
  form.link_video = demo.url;
  form.titulo = demo.title;
  form.es_promocion = false;
  form.fechaInicio = '';
  form.horaInicio = '';
  form.fechaFin = '';
  form.horaFin = '';
  form.fecha_inicio = null;
  form.fecha_fin = null;
};
</script>

<style scoped>
.paint-order-stroke {
  paint-order: stroke fill;
}
.stroke-black {
  -webkit-text-stroke: 5px #000000;
}
</style>
