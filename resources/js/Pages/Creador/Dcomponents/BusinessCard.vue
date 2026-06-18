<script setup>
import LogoSection from './LogoSection.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    negocio: Object,
    // Si es true usa la ruta pública de usuario, si es false usa la de creador
    rutaPublica: {
        type: Boolean,
        default: false
    }
})

const imageUrl = props.negocio?.foto_fachada
    ? '/storage/' + props.negocio.foto_fachada
    : '/imabusiness/king.jpg'

const destino = props.rutaPublica
    ? route('negocio.publico', props.negocio.id)
    : route('creador.vista-negocio', props.negocio.id)
</script>

<template>
    <Link
        :href="destino"
        class="block border-2 border-gray-200 rounded-2xl mt-4 hover:border-blue-400 hover:shadow-xl transition-all duration-300 overflow-hidden bg-white cursor-pointer group"
    >
        <!-- Imagen de portada -->
        <div class="relative overflow-hidden h-44">
            <img
                :src="imageUrl"
                alt="Portada del negocio"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            >
            <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                ✓ Activo
            </span>
            <!-- Overlay hover -->
            <div class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 transition-all duration-300 flex items-center justify-center">
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white text-blue-700 font-bold text-xs px-4 py-2 rounded-full shadow-lg uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver negocio
                </span>
            </div>
        </div>

        <!-- Logo y nombre -->
        <div class="pt-2 px-4 pb-5 border-t border-gray-100">
            <LogoSection :negocio="negocio" />
        </div>
    </Link>
</template>
