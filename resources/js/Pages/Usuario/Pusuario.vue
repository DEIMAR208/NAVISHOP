<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Header from '../../Components/Header.vue';
import Menu from '../../Components/Menu.vue';

const props = defineProps({
    promociones: {
        type: Array,
        default: () => []
    }
});

// Buscador y filtros
const searchQuery = ref('');
const selectedCategory = ref('');

// Formateo de fechas
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    });
};

// Obtener rutas de imágenes
const getPromoImg = (promo) => {
    return promo.foto 
        ? '/storage/' + promo.foto 
        : '/imabusiness/king.jpg';
};

const getLogoImg = (negocio) => {
    return negocio?.logo 
        ? '/storage/' + negocio.logo 
        : '/imabusiness/loking.png';
};

// Calcular días restantes
const getDaysRemaining = (fechaFin) => {
    if (!fechaFin) return null;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const end = new Date(fechaFin);
    end.setHours(0, 0, 0, 0);
    const diffTime = end - today;
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

// Determinar si la promoción está activa
const isPromoActive = (fechaFin) => {
    if (!fechaFin) return true;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const end = new Date(fechaFin);
    end.setHours(23, 59, 59, 999);
    return today <= end;
};

// Extraer categorías únicas de los negocios que tienen promociones
const categoriasUnicas = computed(() => {
    const categorias = props.promociones
        .map(p => p.negocio?.tipo)
        .filter(tipo => tipo);
    return [...new Set(categorias)];
});

// Filtrar promociones
const promocionesFiltradas = computed(() => {
    return props.promociones.filter(promo => {
        // Solo mostrar promociones activas
        if (!isPromoActive(promo.fecha_fin)) return false;

        const matchQuery = 
            (promo.titulo && promo.titulo.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
            (promo.descripcion && promo.descripcion.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
            (promo.negocio && promo.negocio.nombre && promo.negocio.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()));

        const matchCategory = 
            !selectedCategory.value || 
            (promo.negocio && promo.negocio.tipo === selectedCategory.value);

        return matchQuery && matchCategory;
    });
});

// Limpiar todos los filtros
const clearFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = '';
};
</script>

<template>
    <div class="fixed inset-0 flex flex-col bg-slate-50 overflow-hidden">
        <Header />

        <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
            <!-- Sidebar Menu -->
            <Menu class="hidden md:flex animate-fade-in" />

            <!-- Contenido Principal -->
            <div class="flex-1 p-4 sm:p-6 md:p-8 max-w-7xl w-full mx-auto overflow-y-auto">


            <!-- Filtros y Buscador -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        🔍
                    </span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Buscar promoción o negocio..." 
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition-all text-slate-700 font-medium"
                    />
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    <select 
                        v-model="selectedCategory"
                        class="flex-1 md:flex-none px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-700 font-semibold cursor-pointer"
                    >
                        <option value="">Todas las categorías</option>
                        <option v-for="cat in categoriasUnicas" :key="cat" :value="cat">
                            {{ cat }}
                        </option>
                    </select>

                    <button 
                        v-if="searchQuery || selectedCategory"
                        @click="clearFilters"
                        class="px-4 py-3 text-sm text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 rounded-xl transition-all font-semibold active:scale-95"
                    >
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- Feed de Promociones: imagen izquierda / info derecha -->
            <div v-if="promocionesFiltradas.length > 0" class="flex flex-col gap-6 pb-8">
                <div
                    v-for="promo in promocionesFiltradas"
                    :key="promo.id"
                    class="w-full bg-white rounded-3xl border border-slate-100 shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group flex flex-col md:flex-row"
                    style="height: 480px;"
                >
                    <!-- IZQUIERDA: Imagen grande 65% -->
                    <div class="relative overflow-hidden bg-slate-900 w-full md:w-[65%] flex-shrink-0" style="min-height: 240px;">
                        <img
                            :src="getPromoImg(promo)"
                            alt="Imagen de promoción"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <!-- Overlay degradado hacia la derecha -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/30"></div>
                        <!-- Overlay inferior -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-transparent"></div>

                        <!-- Badges arriba izquierda -->
                        <div class="absolute top-5 left-5 flex flex-col gap-2">
                            <span
                                v-if="getDaysRemaining(promo.fecha_fin) !== null"
                                :class="[
                                    'text-xs font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-xl backdrop-blur-sm border text-white w-fit',
                                    getDaysRemaining(promo.fecha_fin) <= 1
                                        ? 'bg-rose-500/95 border-rose-400'
                                        : getDaysRemaining(promo.fecha_fin) <= 3
                                            ? 'bg-amber-500/95 border-amber-400'
                                            : 'bg-emerald-500/95 border-emerald-400'
                                ]"
                            >
                                {{
                                    getDaysRemaining(promo.fecha_fin) === 0
                                        ? '¡Vence hoy!'
                                        : getDaysRemaining(promo.fecha_fin) === 1
                                            ? 'Último día'
                                            : `Quedan ${getDaysRemaining(promo.fecha_fin)} días`
                                }}
                            </span>
                            <span v-if="promo.negocio?.tipo" class="bg-black/65 border border-white/20 text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full shadow-lg backdrop-blur-sm w-fit">
                                {{ promo.negocio.tipo }}
                            </span>
                        </div>

                        <!-- Negocio info — abajo izquierda sobre la imagen -->
                        <div class="absolute bottom-5 left-5 flex items-center gap-3">
                            <img
                                :src="getLogoImg(promo.negocio)"
                                alt="Logo negocio"
                                class="w-14 h-14 rounded-full border-2 border-white object-cover shadow-xl bg-white"
                            />
                            <div>
                                <p class="text-white font-black text-lg leading-tight drop-shadow-lg">{{ promo.negocio?.nombre }}</p>
                                <p class="text-white/75 text-xs font-semibold">{{ promo.negocio?.tipo || 'Negocio local' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- DERECHA: Información 35% -->
                    <div class="flex flex-col justify-between p-7 md:p-9 flex-1 bg-white">
                        <div class="space-y-5">
                            <h2 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                                {{ promo.titulo }}
                            </h2>
                            <div class="w-14 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                            <p class="text-slate-500 text-sm md:text-base leading-relaxed">
                                {{ promo.descripcion || 'Sin descripción disponible.' }}
                            </p>
                            <!-- Mini card del negocio -->
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <img :src="getLogoImg(promo.negocio)" alt="Logo" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm" />
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ promo.negocio?.nombre }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ promo.negocio?.tipo || 'Negocio local' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas + botón -->
                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">📅 Inicio</p>
                                    <p class="text-sm font-black text-slate-800">{{ formatDate(promo.fecha_inicio) }}</p>
                                </div>
                                <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 text-center">
                                    <p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider mb-1">⌛ Vence</p>
                                    <p class="text-sm font-black text-rose-700">{{ formatDate(promo.fecha_fin) }}</p>
                                </div>
                            </div>
                            <Link
                                :href="route('negocio.publico', promo.negocio_id)"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-center py-4 px-4 rounded-2xl text-sm font-black transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-blue-200 active:scale-[0.98] hover:-translate-y-0.5"
                            >
                                Visitar Negocio
                                <span class="text-lg">🏪</span>
                            </Link>
                        </div>
                    </div>

                </div>
            </div>



            <!-- Estado Vacío -->
            <div v-else class="bg-white rounded-3xl border border-slate-100 shadow-sm p-12 text-center max-w-lg mx-auto mt-12">
                <span class="text-5xl block mb-4">📢</span>
                <h3 class="text-xl font-bold text-slate-800">No hay promociones activas</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                    {{ searchQuery || selectedCategory 
                        ? 'Ninguna promoción activa coincide con tus filtros de búsqueda. Intenta limpiando los campos.' 
                        : 'Por el momento no hay promociones publicadas. ¡Vuelve más tarde para ver ofertas espectaculares!' 
                    }}
                </p>
                <button 
                    v-if="searchQuery || selectedCategory"
                    @click="clearFilters"
                    class="mt-5 px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all"
                >
                    Ver todas las promociones
                </button>
            </div>
        </div>
    </div>
  </div>
</template>
