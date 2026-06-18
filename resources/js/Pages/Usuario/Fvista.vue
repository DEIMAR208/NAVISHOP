<template>
    <div class="fixed inset-0 flex flex-col bg-gray-50 font-sans text-gray-800 overflow-hidden">

        <!-- HEADER DE USUARIO -->
        <Header />

        <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
            <!-- MENÚ LATERAL DE USUARIO -->
            <Menu class="hidden md:flex" />

            <!-- CONTENIDO -->
            <div class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                        
                        <!-- COLUMNA IZQUIERDA: Fachada, Logo, Nombre, Contacto y Redes -->
                        <div class="order-1 lg:col-span-1 space-y-6">
                            
                            <!-- TARJETA DE PERFIL (Fachada, Logo y Nombre) -->
                            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                                <!-- Portada Fachada miniatura -->
                                <div class="relative w-full h-44 bg-slate-900 overflow-hidden">
                                    <!-- Fondo difuminado -->
                                    <img
                                        v-if="negocio.foto_fachada"
                                        :src="'/storage/' + negocio.foto_fachada"
                                        alt=""
                                        class="absolute inset-0 w-full h-full object-cover blur-md opacity-30 scale-105 select-none pointer-events-none"
                                    />
                                    <img
                                        v-if="negocio.foto_fachada"
                                        :src="'/storage/' + negocio.foto_fachada"
                                        alt="Portada del negocio"
                                        class="relative w-full h-full object-contain mx-auto z-10"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-white/60 flex-col gap-2 relative z-10">
                                        <span class="text-4xl">🏪</span>
                                        <span class="text-xs font-semibold uppercase tracking-wider">Sin portada</span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent z-10"></div>
                                </div>

                                <!-- Logo y Nombre -->
                                <div class="relative flex flex-col items-center px-4 pb-6">
                                    <div class="relative -mt-16 w-32 h-32 rounded-full bg-white border-4 border-white shadow-xl overflow-hidden flex items-center justify-center z-20">
                                        <img v-if="negocio.logo" :src="'/storage/' + negocio.logo" alt="Logo" class="w-full h-full object-cover" />
                                        <span v-else class="text-5xl">🍔</span>
                                    </div>
                                    <h1 class="mt-3 text-xl font-black text-gray-900 uppercase tracking-wide text-center">
                                        {{ negocio.nombre }}
                                    </h1>
                                    <span class="mt-1.5 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">
                                        🏷️ {{ negocio.tipo }}
                                    </span>
                                    <div class="mt-2 flex items-center gap-3">
                                        <span :class="[
                                            'px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wide border',
                                            estaAbierto
                                                ? 'bg-green-50 text-green-700 border-green-200'
                                                : 'bg-red-50 text-red-600 border-red-200'
                                        ]">
                                            {{ estaAbierto ? 'Abierto' : 'Cerrado' }}
                                        </span>
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ formatTime(negocio.hora_apertura) }} – {{ formatTime(negocio.hora_cierre) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- INFORMACIÓN DE CONTACTO -->
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">📍 Dirección</p>
                                    <p class="text-sm text-gray-700 font-medium">{{ negocio.direccion || 'No especificada' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">📱 Teléfono</p>
                                    <a :href="'tel:' + negocio.telefono" class="text-sm text-blue-600 font-semibold hover:underline">
                                        {{ negocio.telefono || 'No especificado' }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">✉️ Correo</p>
                                    <a :href="'mailto:' + negocio.correo" class="text-sm text-blue-600 font-semibold hover:underline break-all">
                                        {{ negocio.correo || 'No especificado' }}
                                    </a>
                                </div>
                                <div v-if="negocio.nit">
                                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">📄 NIT</p>
                                    <p class="text-sm text-gray-700 font-medium">{{ negocio.nit }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">🗓️ Días de atención</p>
                                    <p class="text-sm text-gray-700 font-medium">{{ formatDias(negocio.dias_trabajo) }}</p>
                                </div>
                            </div>

                            <!-- REDES SOCIALES -->
                            <div v-if="negocio.facebook || negocio.instagram || negocio.tiktok"
                                class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-3">🌐 Redes Sociales</p>
                                <div class="flex gap-3">
                                    <a v-if="negocio.facebook" :href="negocio.facebook" target="_blank"
                                        class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center hover:bg-blue-100 transition text-xl shadow-sm border border-blue-200/20" title="Facebook">📘</a>
                                    <a v-if="negocio.instagram" :href="negocio.instagram" target="_blank"
                                        class="w-11 h-11 bg-pink-50 rounded-xl flex items-center justify-center hover:bg-pink-100 transition text-xl shadow-sm border border-pink-200/20" title="Instagram">📸</a>
                                    <a v-if="negocio.tiktok" :href="negocio.tiktok" target="_blank"
                                        class="w-11 h-11 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition text-xl shadow-sm border border-gray-200" title="TikTok">🎵</a>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: Ubicación/Mapa, Promociones y Catálogo -->
                        <div class="order-2 lg:col-span-2 space-y-6">
                            <!-- MAPA / UBICACIÓN -->
                            <div v-if="negocio.google_maps" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-3">📍 Ubicación del Negocio</p>
                                <div v-if="isGoogleMapsEmbed(negocio.google_maps)" class="rounded-xl overflow-hidden border border-gray-200">
                                    <iframe :src="negocio.google_maps" class="w-full h-[260px] border-0" allowfullscreen loading="lazy"></iframe>
                                </div>
                                <div v-else class="flex flex-col items-center justify-center py-6 gap-3 bg-gray-50 rounded-xl border border-gray-200">
                                    <span class="text-4xl animate-bounce">🗺️</span>
                                    <a :href="negocio.google_maps" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow transition uppercase tracking-wider">
                                        🌐 Ver en Google Maps
                                    </a>
                                </div>
                            </div>

                            <!-- ── BOTONES DE PESTAÑA: PROMOCIONES / CATÁLOGO ── -->
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    @click="pestañaActiva = 'promociones'"
                                    :class="[
                                        'flex items-center justify-center gap-2 h-14 font-bold rounded-2xl shadow-md transition-all duration-200 text-xs sm:text-sm px-2 uppercase tracking-wider transform hover:-translate-y-0.5 cursor-pointer',
                                        pestañaActiva === 'promociones'
                                            ? 'bg-emerald-600 text-white shadow-emerald-600/20'
                                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                                    ]"
                                >
                                    🔥 Promociones
                                </button>
                                <button
                                    @click="pestañaActiva = 'catalogo'"
                                    :class="[
                                        'flex items-center justify-center gap-2 h-14 font-bold rounded-2xl shadow-md transition-all duration-200 text-xs sm:text-sm px-2 uppercase tracking-wider transform hover:-translate-y-0.5 cursor-pointer',
                                        pestañaActiva === 'catalogo'
                                            ? 'bg-blue-600 text-white shadow-blue-600/20'
                                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                                    ]"
                                >
                                    📖 Catálogo
                                </button>
                            </div>

                            <!-- PESTAÑA: PROMOCIONES -->
                            <div v-show="pestañaActiva === 'promociones'">
                                <div v-if="negocio.promociones && negocio.promociones.length"
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div v-for="promo in negocio.promociones" :key="promo.id"
                                        class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-250">
                                        <div class="relative">
                                            <img v-if="promo.foto" :src="'/storage/' + promo.foto" alt="Promoción" class="w-full h-44 object-cover" />
                                            <div v-else class="w-full h-44 bg-gradient-to-br from-emerald-50 to-teal-100 flex items-center justify-center text-4xl">🎁</div>
                                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">ACTIVA</span>
                                        </div>
                                        <div class="p-5 space-y-2">
                                            <h3 class="font-bold text-gray-900 text-base leading-tight">{{ promo.titulo }}</h3>
                                            <p class="text-xs text-gray-500 leading-relaxed">{{ promo.descripcion }}</p>
                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 pt-1 text-[11px] text-gray-500 border-t border-gray-100 mt-2">
                                                <span>📅 Inicia: <strong>{{ formatDate(promo.fecha_inicio) }}</strong></span>
                                                <span>⌛ Fin: <strong>{{ formatDate(promo.fecha_fin) }}</strong></span>
                                            </div>
                                            <button class="w-full mt-2 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl transition-all text-xs uppercase tracking-wide shadow cursor-pointer">
                                                🎁 Reclamar oferta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-12 text-gray-400 space-y-2 bg-white rounded-2xl border border-gray-200 shadow-sm">
                                    <span class="text-4xl">🎟️</span>
                                    <p class="text-sm font-medium">No hay promociones activas por el momento.</p>
                                </div>
                            </div>

                            <!-- PESTAÑA: CATÁLOGO -->
                            <div v-show="pestañaActiva === 'catalogo'">
                                <div v-if="negocio.catalogos && negocio.catalogos.length">
                                    <div v-for="catalogo in negocio.catalogos" :key="catalogo.id" class="mb-8 bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
                                        <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                                            <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">{{ catalogo.titulo }}</h3>
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full font-semibold">
                                                {{ catalogo.productos ? catalogo.productos.length : 0 }} productos
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                            <div v-for="producto in catalogo.productos" :key="producto.id"
                                                class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200">
                                                <div class="w-full h-32 bg-gray-50 flex items-center justify-center overflow-hidden">
                                                    <img v-if="producto.imagen" :src="'/storage/' + producto.imagen" alt="Producto" class="w-full h-full object-cover" />
                                                    <span class="text-3xl" v-else>🖼️</span>
                                                </div>
                                                <div class="p-3">
                                                    <h4 class="font-bold text-gray-800 text-sm truncate">{{ producto.nombre }}</h4>
                                                    <p v-if="producto.descripcion" class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ producto.descripcion }}</p>
                                                    <span class="inline-block mt-1.5 text-green-600 font-black text-base">Bs {{ producto.precio }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-12 text-gray-400 space-y-2 bg-white rounded-2xl border border-gray-200 shadow-sm">
                                    <span class="text-4xl">📁</span>
                                    <p class="text-sm font-medium">No hay catálogos publicados aún.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import Menu from '@/Components/Menu.vue';
import Header from '@/Components/Header.vue';

const props = defineProps({
    negocio: Object
});

const pestañaActiva = ref('promociones');

const formatTime = (t) => {
    if (!t) return '';
    const parts = t.split(':');
    const h = parseInt(parts[0], 10);
    const m = parts[1];
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = h % 12 || 12;
    return `${h12}:${m} ${ampm}`;
};

const formatDate = (d) => {
    if (!d) return '';
    try {
        return new Date(d).toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
    } catch { return d; }
};

const formatDias = (dias) => {
    if (!dias) return 'No especificado';
    if (Array.isArray(dias)) return dias.join(', ');
    try {
        const parsed = JSON.parse(dias);
        if (Array.isArray(parsed)) return parsed.join(', ');
    } catch {}
    return dias;
};

const isGoogleMapsEmbed = (url) => {
    return url && (url.includes('google.com/maps/embed') || url.includes('<iframe'));
};

const estaAbierto = computed(() => {
    const n = props.negocio;
    if (!n || !n.dias_trabajo || !n.hora_apertura || !n.hora_cierre) return false;
    const now = new Date();
    const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const diaActual = diasSemana[now.getDay()];
    const normalize = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const dias = Array.isArray(n.dias_trabajo) ? n.dias_trabajo :
        (typeof n.dias_trabajo === 'string' ? (() => { try { return JSON.parse(n.dias_trabajo); } catch { return [n.dias_trabajo]; } })() : []);
    if (!dias.some(d => normalize(d) === normalize(diaActual))) return false;
    const min = now.getHours() * 60 + now.getMinutes();
    const parse = (t) => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
    const open = parse(n.hora_apertura);
    const close = parse(n.hora_cierre);
    return close > open ? (min >= open && min <= close) : (min >= open || min <= close);
});
</script>
