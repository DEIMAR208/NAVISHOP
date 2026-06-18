<script setup>
import BusinessCard from '../Creador/Dcomponents/BusinessCard.vue';

const props = defineProps({
    negocios: Array
});
</script>

<template>
    <div class="p-4 md:p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Negocios Registrados</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <BusinessCard 
                v-for="negocio in negocios" 
                :key="negocio.id" 
                :negocio="negocio" 
            />
        </div>
    </div>
</template>