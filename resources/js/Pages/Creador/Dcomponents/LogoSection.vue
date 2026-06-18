<script setup>
const props = defineProps({
    negocio: Object
})

const logoUrl = props.negocio?.logo
    ? '/storage/' + props.negocio.logo
    : '/imabusiness/loking.png'

const negocioNombre = props.negocio?.nombre || 'BURQUE KING'
</script>

<template>
    <div class="flex items-center gap-4 w-full py-3">
        <div class="flex-shrink-0">
            <img
                :src="logoUrl"
                alt="logo circular"
                class="w-14 h-14 rounded-full object-cover border border-gray-150 shadow-sm"
            >
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-base font-extrabold text-gray-800 truncate group-hover:text-blue-600 transition-colors">
                {{ negocioNombre }}
            </h2>
            <p v-if="negocio?.tipo" class="text-xs text-gray-400 font-semibold truncate mt-0.5">
                🏷️ {{ negocio.tipo }}
            </p>
        </div>
    </div>
</template>
