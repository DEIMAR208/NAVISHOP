<template>
    <div class="fixed inset-0 flex flex-col bg-gray-50 text-gray-800 font-sans overflow-hidden">
        <Header />

        <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
            <Menucreador class="hidden md:flex" />

        <!-- CASO 1: No hay negocio registrado -->
        <main v-if="!negocio" class="flex-1 p-6 flex flex-col items-center justify-center max-w-7xl mx-auto w-full text-center overflow-y-auto">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 max-w-lg w-full space-y-6 transition-all duration-300 hover:shadow-2xl">
                <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto text-4xl animate-bounce">
                    🏪
                </div>
                <div class="space-y-2">
                    <h1 class="text-2xl font-black text-gray-900 tracking-wide">¡Aún no tienes un negocio registrado!</h1>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Para acceder a todas las herramientas de creador y gestionar tu catálogo o promociones, primero debes registrar la información de tu negocio.
                    </p>
                </div>
                <div class="pt-4">
                    <Link 
                        :href="route('fusuario')" 
                        class="inline-flex items-center justify-center w-full px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 uppercase tracking-wider text-sm"
                    >
                        🚀 Registrar mi Negocio
                    </Link>
                </div>
            </div>
        </main>

        <!-- CASO 2: Negocio registrado -->
        <main v-else class="flex-1 p-6 space-y-6 max-w-7xl mx-auto w-full overflow-y-auto">
            
            <!-- Tarjeta de Encabezado (Portada y Logo) -->
            <div class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden relative pb-10">
                <!-- Imagen de Portada (Fachada) -->
                <div class="w-full h-[180px] bg-slate-900 flex items-center justify-center relative overflow-hidden group">
                    <!-- Fondo difuminado de la misma imagen para rellenar los bordes lateralmente de forma premium -->
                    <img 
                        v-if="negocio.foto_fachada" 
                        :src="'/storage/' + negocio.foto_fachada" 
                        alt="" 
                        class="absolute inset-0 w-full h-full object-cover blur-md opacity-35 scale-105 select-none pointer-events-none"
                    />
                    <img 
                        v-if="negocio.foto_fachada" 
                        :src="'/storage/' + negocio.foto_fachada" 
                        alt="Portada Fachada" 
                        class="relative w-full h-full object-contain mx-auto z-10"
                    />
                    <div v-else class="text-white/80 flex flex-col items-center gap-2 relative z-10">
                        <span class="text-4xl">🖼️</span>
                        <span class="text-xs font-semibold tracking-wider uppercase">Sin portada de negocio</span>
                    </div>
                    
                    <!-- Overlay degradado -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent z-10"></div>
                </div>

                <!-- Contenedor del Logo -->
                <div class="absolute top-[130px] left-1/2 transform -translate-x-1/2 w-28 h-28 bg-white border-4 border-white rounded-full shadow-lg flex items-center justify-center overflow-hidden z-10">
                    <img 
                        v-if="negocio.logo" 
                        :src="'/storage/' + negocio.logo" 
                        alt="Logo Negocio" 
                        class="w-full h-full object-cover"
                    />
                    <span v-else class="text-4xl">🍔</span>
                </div>

                <!-- Nombre y Categoría -->
                <div class="pt-16 text-center px-4">
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-wide uppercase">{{ negocio.nombre }}</h1>
                    <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">
                        🏷️ {{ negocio.tipo }}
                    </span>
                </div>
            </div>

            <!-- Grid de dos columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Izquierda: Horario, Contacto y Redes -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Tarjeta de Horario -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl">🕒</span>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Horario de Atención</h3>
                            </div>
                            <span 
                                :class="[
                                    'px-2.5 py-1 text-xs font-black rounded-full uppercase tracking-wide border',
                                    estaAbierto 
                                        ? 'bg-green-50 text-green-700 border-green-200 animate-pulse' 
                                        : 'bg-red-50 text-red-650 border-red-200'
                                ]"
                            >
                                {{ estaAbierto ? 'Abierto Ahora' : 'Cerrado' }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Días Laborales</p>
                                <p class="text-sm font-semibold text-gray-700 mt-0.5">
                                    {{ formatDiasTrabajo(negocio.dias_trabajo) }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Apertura</p>
                                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ formatTime(negocio.hora_apertura) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Cierre</p>
                                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ formatTime(negocio.hora_cierre) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de Contacto y Redes -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-5">
                        <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                            <span class="text-xl">📞</span>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Contacto</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="text-lg text-gray-400">📱</span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Teléfono</p>
                                    <a :href="'tel:' + negocio.telefono" class="text-sm font-semibold text-blue-600 hover:underline">{{ negocio.telefono }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-lg text-gray-400">✉️</span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Correo Electrónico</p>
                                    <a :href="'mailto:' + negocio.correo" class="text-sm font-semibold text-blue-600 hover:underline break-all">{{ negocio.correo }}</a>
                                </div>
                            </div>
                            <div v-if="negocio.nit" class="flex items-center gap-3">
                                <span class="text-lg text-gray-400">📄</span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">NIT</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ negocio.nit }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Redes Sociales -->
                        <div v-if="negocio.facebook || negocio.instagram || negocio.tiktok" class="pt-4 border-t border-gray-100 space-y-3">
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Redes Sociales</p>
                            <div class="flex gap-3">
                                <a 
                                    v-if="negocio.facebook" 
                                    :href="negocio.facebook" 
                                    target="_blank" 
                                    class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition duration-200 text-lg"
                                    title="Facebook"
                                >
                                    📘
                                </a>
                                <a 
                                    v-if="negocio.instagram" 
                                    :href="negocio.instagram" 
                                    target="_blank" 
                                    class="w-10 h-10 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center hover:bg-pink-100 transition duration-200 text-lg"
                                    title="Instagram"
                                >
                                    📸
                                </a>
                                <a 
                                    v-if="negocio.tiktok" 
                                    :href="negocio.tiktok" 
                                    target="_blank" 
                                    class="w-10 h-10 bg-gray-55 text-gray-800 rounded-xl flex items-center justify-center hover:bg-gray-100 transition duration-200 text-lg border border-gray-200"
                                    title="TikTok"
                                >
                                    🎵
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Catálogos, Promociones y Ubicación -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Botones de Pestañas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                        <button 
                            @click="pestañaActiva = 'catalogos'" 
                            :class="[
                                'flex items-center justify-center gap-2 h-[60px] font-bold rounded-2xl shadow-md transition-all duration-200 text-base uppercase tracking-wider transform hover:-translate-y-0.5 active:translate-y-0',
                                pestañaActiva === 'catalogos' ? 'bg-blue-600 text-white shadow-blue-600/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                            ]"
                        >
                            📖 Mis Catálogos
                        </button>
                        <button 
                            @click="pestañaActiva = 'promociones'" 
                            :class="[
                                'flex items-center justify-center gap-2 h-[60px] font-bold rounded-2xl shadow-md transition-all duration-200 text-base uppercase tracking-wider transform hover:-translate-y-0.5 active:translate-y-0',
                                pestañaActiva === 'promociones' ? 'bg-emerald-600 text-white shadow-emerald-600/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                            ]"
                        >
                            🔥 Mis Promociones
                        </button>
                    </div>

                    <!-- Pestaña: Catálogos -->
                    <div v-show="pestañaActiva === 'catalogos'" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4 animate-fade-in">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <h3 class="text-base font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                <span>📖</span> Catálogos del Negocio
                            </h3>
                            <div class="flex items-center gap-4">
                                <button v-if="!vistaDetalleCatalogos" @click="vistaDetalleCatalogos = true" class="text-xs font-bold text-emerald-600 hover:underline flex items-center gap-1 uppercase tracking-wide">
                                    👁️ Ver Todos
                                </button>
                                <button v-else @click="vistaDetalleCatalogos = false" class="text-xs font-bold text-red-500 hover:underline flex items-center gap-1 uppercase tracking-wide">
                                    ⬅️ Ocultar Detalles
                                </button>
                                <Link :href="route('creador.catalogo') + '?edit=1'" class="text-xs font-bold text-amber-600 hover:underline flex items-center gap-1">
                                    ✏️ Editar Catálogo
                                </Link>
                                <Link :href="route('creador.catalogo')" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Nuevo Catálogo
                                </Link>
                            </div>
                        </div>

                        <div v-if="!negocio.catalogos || !negocio.catalogos.length" class="text-center py-8 text-gray-400 space-y-2">
                            <span class="text-3xl">📁</span>
                            <p class="text-sm font-medium">No has creado ningún catálogo de productos aún.</p>
                        </div>

                        <div v-else>
                            <!-- Vista Resumen -->
                            <div v-if="!vistaDetalleCatalogos" class="space-y-3">
                                <div 
                                    v-for="cat in negocio.catalogos" 
                                    :key="cat.id"
                                    @click="vistaDetalleCatalogos = true"
                                    class="flex justify-between items-center p-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition duration-200 cursor-pointer"
                                >
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">{{ cat.titulo }}</h4>
                                        <p class="text-xs text-gray-500 font-medium">
                                            📦 {{ cat.productos ? cat.productos.length : 0 }} productos registrados
                                        </p>
                                    </div>
                                    <div class="px-3.5 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg shadow-sm transition duration-150">
                                        Ver Detalles
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vista Detallada (VCatalogo en línea) -->
                            <div v-else class="space-y-6 pt-2">
                                <VCatalogo 
                                    v-for="cat in negocio.catalogos" 
                                    :key="cat.id" 
                                    :catalogo="cat" 
                                    :negocio_nombre="negocio.nombre" 
                                    :compact-mode="true"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña: Promociones -->
                    <div v-show="pestañaActiva === 'promociones'" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4 animate-fade-in">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <h3 class="text-base font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                <span>🔥</span> Promociones Especiales
                            </h3>
                            <div class="flex items-center gap-4">
                                <button v-if="!vistaDetallePromos" @click="vistaDetallePromos = true" class="text-xs font-bold text-emerald-600 hover:underline flex items-center gap-1 uppercase tracking-wide">
                                    👁️ Ver Todas
                                </button>
                                <button v-else @click="vistaDetallePromos = false" class="text-xs font-bold text-red-500 hover:underline flex items-center gap-1 uppercase tracking-wide">
                                    ⬅️ Ocultar Detalles
                                </button>
                                <Link :href="route('creador.crear-promociones')" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Crear Promoción
                                </Link>
                            </div>
                        </div>

                        <div v-if="!negocio.promociones || !negocio.promociones.length" class="text-center py-8 text-gray-400 space-y-2">
                            <span class="text-3xl">🎟️</span>
                            <p class="text-sm font-medium">No tienes promociones activas registradas.</p>
                        </div>
                        
                        <div v-else>
                            <!-- Vista Resumen -->
                            <div v-if="!vistaDetallePromos" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div 
                                    v-for="promo in negocio.promociones" 
                                    :key="promo.id"
                                    @click="vistaDetallePromos = true"
                                    class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col bg-gray-50 cursor-pointer hover:border-blue-300"
                                >
                                    <div class="w-full h-36 bg-gray-150 relative overflow-hidden flex items-center justify-center">
                                        <img 
                                            v-if="promo.foto"
                                            :src="'/storage/' + promo.foto" 
                                            alt="Foto Promo" 
                                            class="w-full h-full object-cover"
                                        />
                                        <span v-else class="text-3xl">🎁</span>
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm line-clamp-1 uppercase tracking-wide">{{ promo.titulo }}</h4>
                                            <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ promo.descripcion }}</p>
                                        </div>
                                        <div class="text-[10px] bg-white border border-gray-200 rounded-lg p-2 flex flex-col gap-1">
                                            <div class="flex justify-between text-gray-600">
                                                <span class="font-semibold">INICIA:</span>
                                                <span>{{ formatDate(promo.fecha_inicio) }}</span>
                                            </div>
                                            <div class="flex justify-between text-gray-600">
                                                <span class="font-semibold">FIN:</span>
                                                <span>{{ formatDate(promo.fecha_fin) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vista Detallada -->
                            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
                                <div
                                    v-for="promocion in negocio.promociones"
                                    :key="promocion.id"
                                    class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 flex flex-col"
                                >
                                    <div class="p-6 pb-4 flex justify-between items-start gap-4">
                                        <h2 class="text-xl font-bold text-gray-900 leading-tight">
                                            {{ promocion.titulo }}
                                        </h2>
                                        <Edishio @click="abrirModalEditarPromo(promocion)" />
                                    </div>

                                    <div class="relative px-6">
                                        <div class="relative rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center border border-gray-100">
                                            <img
                                            :src="promocion.foto ? '/storage/' + promocion.foto : '/placeholder-promo.jpg'"
                                            alt="Promoción"
                                            class="w-full h-48 object-contain"
                                            />
                                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                                ACTIVA
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                        <div class="flex items-start space-x-2">
                                            <span class="text-lg">📅</span>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase font-medium">Inicia:</p>
                                                <p class="text-xs font-semibold text-gray-900">{{ formatDate(promocion.fecha_inicio) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="text-lg">⌛</span>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase font-medium">Finaliza:</p>
                                                <p class="text-xs font-semibold text-gray-900">{{ formatDate(promocion.fecha_fin) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-6 pb-6 flex flex-col justify-between flex-1">
                                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line mb-4">
                                            {{ mostrarCompleto[promocion.id] ? promocion.descripcion : (promocion.descripcion?.length > 120 ? promocion.descripcion.substring(0, 120) + '...' : promocion.descripcion) }}
                                            <button
                                            v-if="promocion.descripcion?.length > 120"
                                            @click="mostrarCompleto[promocion.id] = !mostrarCompleto[promocion.id]"
                                            class="text-blue-600 font-bold hover:text-blue-800 ml-1"
                                            >
                                            {{ mostrarCompleto[promocion.id] ? 'Ver menos' : 'Ver más' }}
                                            </button>
                                        </p>

                                        <button
                                            @click="reclamarOferta(promocion)"
                                            class="w-full mt-auto bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 px-6 rounded-xl shadow hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wide text-xs"
                                        >
                                            🎁 Reclamar Oferta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Ubicación -->
                    <div class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                            <span class="text-xl text-red-500">📍</span>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Nuestra Ubicación</h3>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">{{ negocio.direccion }}</p>
                        
                        <!-- Contenedor del Mapa -->
                        <div v-if="negocio.google_maps" class="w-full rounded-xl overflow-hidden border border-gray-200 shadow-sm relative group">
                            <!-- Si es embebido de Google Maps -->
                            <div v-if="isGoogleMapsEmbed(negocio.google_maps)" class="w-full h-[280px]">
                                <iframe 
                                    :src="negocio.google_maps" 
                                    class="w-full h-full border-0" 
                                    allowfullscreen="" 
                                    loading="lazy"
                                ></iframe>
                            </div>
                            <!-- Si es URL regular de compartir, mostramos banner de redirección elegante -->
                            <div v-else class="w-full h-[180px] bg-gradient-to-br from-red-50 to-blue-50 flex flex-col items-center justify-center p-6 text-center space-y-3">
                                <span class="text-4xl animate-bounce">🗺️</span>
                                <div class="space-y-1">
                                    <p class="text-sm font-bold text-gray-800">Mapa Interactivo de Google Maps</p>
                                    <p class="text-xs text-gray-500">El usuario registró una ubicación externa para abrir en el mapa.</p>
                                </div>
                                <a 
                                    :href="negocio.google_maps" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-md shadow-red-650/10 transition duration-150 uppercase tracking-wider"
                                >
                                    <span>🌐</span> Abrir Ubicación en Google Maps
                                </a>
                            </div>
                        </div>
                        <!-- En caso de no tener mapa registrado -->
                        <div v-else class="w-full h-[140px] bg-gray-50 border border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-400 text-center p-4">
                            <span class="text-2xl">🗺️</span>
                            <p class="text-xs mt-2 font-medium">No se ha registrado un enlace de Google Maps.</p>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <RRpromocion
            v-if="mostrarModalPromo"
            :promocion-a-editar="promocionSeleccionada"
            @cerrar="mostrarModalPromo = false"
            @guardado="mostrarModalPromo = false"
        />
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Menucreador from './Dcomponents/Menucreador.vue';
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue';
import VCatalogo from './Dcomponents/VCatalogo.vue';
import Edishio from './Dcomponents/Edishio.vue';
import RRpromocion from './Dcomponents/RRpromocion.vue';

const pestañaActiva = ref('catalogos');
const vistaDetalleCatalogos = ref(false);
const vistaDetallePromos = ref(false);
const mostrarCompleto = ref({});

const promocionSeleccionada = ref(null);
const mostrarModalPromo = ref(false);

const abrirModalEditarPromo = (promo) => {
    promocionSeleccionada.value = promo;
    mostrarModalPromo.value = true;
};

const props = defineProps({
    negocio: Object
});

const reclamarOferta = (promo) => {
    alert('¡Has reclamado la oferta exitosamente!');
};

// Obtener array de días de trabajo de forma segura (sea array o string JSON)
const getDiasTrabajoArray = (dias) => {
    if (!dias) return [];
    if (Array.isArray(dias)) return dias;
    if (typeof dias === 'string') {
        try {
            const cleaned = dias.trim();
            if (cleaned.startsWith('[') && cleaned.endsWith(']')) {
                const parsed = JSON.parse(cleaned);
                if (Array.isArray(parsed)) return parsed;
            }
            if (cleaned.includes(',')) {
                return cleaned.split(',').map(s => s.trim());
            }
            if (cleaned !== '') return [cleaned];
        } catch (e) {
            return [];
        }
    }
    return [];
};

// Formatear array de días laborales a un texto legible
const formatDiasTrabajo = (dias) => {
    const diasArray = getDiasTrabajoArray(dias);
    if (!diasArray.length) return 'Días no especificados';
    return diasArray.join(', ');
};

// Formatear hora de formato HH:MM:SS a formato HH:MM AM/PM
const formatTime = (timeStr) => {
    if (!timeStr) return '';
    const parts = timeStr.split(':');
    if (parts.length < 2) return timeStr;
    const hours = parseInt(parts[0], 10);
    const minutes = parts[1];
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const hours12 = hours % 12 || 12;
    return `${hours12}:${minutes} ${ampm}`;
};

// Formatear fecha a formato legible (DD/MM/YYYY)
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    try {
        const date = new Date(dateStr);
        return date.toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return dateStr;
    }
};

// Verificar si el enlace de Google Maps es del tipo embebido o iframe
const isGoogleMapsEmbed = (url) => {
    if (!url) return false;
    return url.includes('google.com/maps/embed') || url.includes('<iframe');
};

// Computado para verificar dinámicamente si el negocio está abierto en este instante
const estaAbierto = computed(() => {
    if (!props.negocio || !props.negocio.dias_trabajo || !props.negocio.hora_apertura || !props.negocio.hora_cierre) {
        return false;
    }

    const now = new Date();
    
    // Obtener día de la semana actual
    const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const diaActual = diasSemana[now.getDay()];
    
    // Normalizar texto para la comparación (por acentos/mayúsculas)
    const normalize = str => str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    
    const diasArray = getDiasTrabajoArray(props.negocio.dias_trabajo);
    const trabajaHoy = diasArray.some(
        d => normalize(d) === normalize(diaActual)
    );
    
    if (!trabajaHoy) return false;

    // Verificar las horas
    const minutosAhora = now.getHours() * 60 + now.getMinutes();

    const parseTimeToMinutes = (t) => {
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    };

    const minApertura = parseTimeToMinutes(props.negocio.hora_apertura);
    const minCierre = parseTimeToMinutes(props.negocio.hora_cierre);

    if (minCierre > minApertura) {
        return minutosAhora >= minApertura && minutosAhora <= minCierre;
    } else {
        // Cierre cruza la medianoche (ej. 18:00 a 02:00)
        return minutosAhora >= minApertura || minutosAhora <= minCierre;
    }
});

</script>

