<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Header from '../../Components/Header.vue'
import Menu from '../../Components/Menu.vue'

const props = defineProps({
  videos: {
    type: Array,
    default: () => []
  }
})

const activeVideoId = ref(null)
const videoElements = ref({})
let observer = null

const setupObserver = () => {
  if (observer) observer.disconnect();
  
  if (props.videos.length > 0) {
    if (!props.videos.some(v => v.id === activeVideoId.value)) {
      activeVideoId.value = props.videos[0].id;
    }
  }

  nextTick(() => {
    Object.values(videoElements.value).forEach((el) => {
      if (el) observer.observe(el);
    });
  });
};

onMounted(() => {
  observer = new IntersectionObserver((entries) => {
    const intersecting = entries.find(entry => entry.isIntersecting);
    if (intersecting) {
      activeVideoId.value = parseInt(intersecting.target.dataset.videoId, 10);
    }
  }, {
    root: null,
    threshold: 0.6
  });

  setupObserver();
});

onUnmounted(() => {
  if (observer) observer.disconnect();
});

watch(() => props.videos, () => {
  setupObserver();
}, { deep: true });

// Parsea el enlace de video y devuelve información clave
const parseVideoUrl = (url) => {
  if (!url) return null;
  const trimmed = url.trim();

  // YouTube Shorts / Videos
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
    return {
      type: 'youtube',
      id: ytId,
      embedUrl: `https://www.youtube.com/embed/${ytId}?autoplay=1&loop=1&playlist=${ytId}`,
      thumbnailUrl: `https://img.youtube.com/vi/${ytId}/hqdefault.jpg`
    };
  }

  // TikTok
  if (trimmed.includes('tiktok.com')) {
    const ttMatch = trimmed.match(/video\/(\d+)/) || trimmed.match(/\b\d{15,22}\b/);
    const ttId = ttMatch ? ttMatch[1] || ttMatch[0] : null;
    if (ttId) {
      return {
        type: 'tiktok',
        id: ttId,
        embedUrl: `https://www.tiktok.com/player/v1/${ttId}?music_info=0&description=0&controls=1&autoplay=1&loop=1`,
        thumbnailUrl: null
      };
    }
  }

  return null;
};

// Intenta desmutear y reproducir con sonido automáticamente cuando se carga el iframe
const onIframeLoad = (event, videoType) => {
  if (videoType !== 'tiktok') return;
  const iframe = event.target;
  if (!iframe) return;

  const sendUnmute = () => {
    try {
      iframe.contentWindow.postMessage({
        'x-tiktok-player': true,
        type: 'unmute'
      }, '*');
      iframe.contentWindow.postMessage({
        'x-tiktok-player': true,
        type: 'play'
      }, '*');
    } catch (e) {
      // Ignorar posibles errores cross-origin
    }
  };

  setTimeout(sendUnmute, 150);
  setTimeout(sendUnmute, 400);
  setTimeout(sendUnmute, 800);
  setTimeout(sendUnmute, 1500);
};

// Genera el estilo del background del poster de video inactivo
const getPosterStyle = (url) => {
  const info = parseVideoUrl(url);
  if (info && info.type === 'youtube' && info.thumbnailUrl) {
    return {
      backgroundImage: `url(${info.thumbnailUrl})`
    };
  }
  return {
    background: 'linear-gradient(135deg, #090d16 0%, #111827 50%, #1f2937 100%)'
  };
};

// Activa el video y lo centra en pantalla con scroll suave
const setActiveVideo = (videoId) => {
  activeVideoId.value = videoId;
  const element = videoElements.value[videoId];
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

const formatDateTime = (dateTimeStr) => {
  if (!dateTimeStr) return '';
  const date = new Date(dateTimeStr);
  return date.toLocaleString('es-ES', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>

<template>
  <Head title="Videos de Negocios" />

  <div class="fixed inset-0 flex flex-col bg-slate-50 text-gray-800 font-sans overflow-hidden">
    <Header />

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <!-- Sidebar Menu -->
      <Menu class="hidden md:flex" />

      <!-- Main Content -->
      <div class="flex-1 p-4 sm:p-6 md:pt-4 md:pb-8 md:px-8 max-w-7xl w-full mx-auto overflow-y-auto space-y-6">

        <!-- Video Grid con el mockup de teléfono (Feed Horizontal) -->
        <div v-if="videos && videos.length > 0" class="flex flex-col items-center gap-16 pt-0 pb-12">
          
          <div 
            v-for="video in videos" 
            :key="video.id"
            :ref="el => { if (el) videoElements[video.id] = el }"
            :data-video-id="video.id"
            class="w-full max-w-4xl bg-white border border-gray-200 rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col lg:flex-row items-center lg:items-stretch p-4 lg:p-6 gap-4 lg:gap-6"
          >
            <!-- LADO IZQUIERDO: Reproductor de Video en Mockup de Teléfono -->
            <div class="relative w-full max-w-[270px] sm:max-w-[300px] aspect-[9/16] bg-gradient-to-b from-slate-800 to-slate-950 rounded-3xl overflow-hidden shadow-2xl border-4 border-gray-900 flex flex-col justify-center p-3 h-auto shrink-0 self-center lg:self-start">
              <!-- Iframe del Video / Poster -->
              <div class="relative w-full h-full rounded-2xl overflow-hidden border-2 border-yellow-500/50 bg-black shadow-[0_0_20px_rgba(0,0,0,0.8)] z-10">
                <!-- Video Activo: Reproductor (Iframe) -->
                <template v-if="video.id === activeVideoId">
                  <iframe 
                    v-if="parseVideoUrl(video.link_video)"
                    :src="parseVideoUrl(video.link_video).embedUrl" 
                    class="w-full h-full"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen
                    @load="onIframeLoad($event, parseVideoUrl(video.link_video).type)"
                  >
                  </iframe>
                  <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-4 text-center">
                    <span class="text-4xl mb-2">📺</span>
                    <p class="text-sm font-bold text-gray-300">Enlace no compatible.</p>
                  </div>
                </template>

                <!-- Video Inactivo: Poster / Cover Premium -->
                <template v-else>
                  <div 
                    class="absolute inset-0 w-full h-full flex flex-col items-center justify-center cursor-pointer group bg-cover bg-center transition-all duration-500"
                    :style="getPosterStyle(video.link_video)"
                    @click="setActiveVideo(video.id)"
                  >
                    <!-- Capa oscura/desenfocada translúcida -->
                    <div class="absolute inset-0 bg-black/60 group-hover:bg-black/50 transition-colors duration-300"></div>
                    
                    <!-- Botón de Reproducción Central -->
                    <div class="relative z-10 w-16 h-16 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:scale-110 group-hover:bg-white/20 transition-all duration-300 shadow-2xl">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-white translate-x-[2px]">
                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                      </svg>
                    </div>

                    <!-- Etiqueta del origen del video (YouTube / TikTok) -->
                    <div class="absolute bottom-4 right-4 z-10 bg-black/75 backdrop-blur-sm px-3 py-1 rounded-full border border-white/10 flex items-center gap-1.5 text-[10px] font-bold tracking-wider uppercase text-gray-300">
                      <span v-if="parseVideoUrl(video.link_video)?.type === 'youtube'" class="text-red-500 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> YouTube Shorts
                      </span>
                      <span v-else-if="parseVideoUrl(video.link_video)?.type === 'tiktok'" class="text-cyan-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span> TikTok
                      </span>
                      <span v-else class="text-yellow-500">Video</span>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <!-- LADO DERECHO: Información y Detalle de Video -->
            <div class="flex-1 flex flex-col justify-between py-2 w-full space-y-4">
              <!-- Información de Negocio -->
              <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                  <img 
                    :src="video.negocio && video.negocio.logo ? '/storage/' + video.negocio.logo : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'" 
                    alt="Logo" 
                    class="w-12 h-12 rounded-full border border-gray-200 object-cover"
                  />
                  <div>
                    <h4 class="font-bold text-gray-900 text-sm">
                      {{ video.negocio ? video.negocio.nombre : 'Negocio' }}
                    </h4>
                    <span class="text-xs text-gray-500 block">
                      {{ video.negocio ? `@${video.negocio.nombre.toLowerCase().replace(/\s+/g, '')}` : '@canal' }}
                    </span>
                  </div>
                </div>
                
                <Link 
                  v-if="video.negocio_id"
                  :href="route('negocio.publico', video.negocio_id)"
                  class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors shadow-sm cursor-pointer"
                >
                  Visitar Negocio
                </Link>
              </div>

              <!-- Título y Descripción -->
              <div class="space-y-3 flex-1">
                <h3 class="text-lg font-black text-gray-900 leading-snug flex items-center gap-2">
                  <span class="text-blue-600">▶️</span> {{ video.titulo }}
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                  {{ video.descripcion || 'Sin descripción disponible.' }}
                </p>
              </div>

              <!-- Promoción y Fechas -->
              <div v-if="video.es_promocion" class="bg-blue-50 border border-blue-100 rounded-2xl p-4 space-y-3">
                <div class="flex items-center gap-2 text-blue-800 font-bold text-xs uppercase tracking-wider">
                  <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-ping"></span>
                  🔥 ¡Promoción Especial de Video!
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                  <div class="bg-white p-2 rounded-xl border border-blue-200/50 flex flex-col">
                    <span class="text-[10px] uppercase font-bold text-gray-400">Inicia</span>
                    <span class="font-bold text-gray-700">{{ formatDateTime(video.fecha_inicio) }}</span>
                  </div>
                  <div class="bg-white p-2 rounded-xl border border-blue-200/50 flex flex-col">
                    <span class="text-[10px] uppercase font-bold text-gray-400">Finaliza</span>
                    <span class="font-bold text-gray-700">{{ formatDateTime(video.fecha_fin) }}</span>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-3xl border border-slate-100 shadow-sm p-12 text-center max-w-lg mx-auto mt-12">
          <span class="text-5xl block mb-4">🎥</span>
          <h3 class="text-xl font-bold text-slate-800">No hay videos disponibles</h3>
          <p class="text-slate-500 text-sm mt-2 leading-relaxed">
            Por el momento no hay videos publicados. ¡Vuelve más tarde para conocer novedades en video!
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
.paint-order-stroke {
  paint-order: stroke fill;
}
.stroke-black {
  -webkit-text-stroke: 5px #000000;
}
</style>
