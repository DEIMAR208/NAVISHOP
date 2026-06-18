<script setup>
import Menucreador from './Dcomponents/Menucreador.vue';
import BusinessCard from './Dcomponents/BusinessCard.vue';
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue';

const props = defineProps({
    misNegocios: Array,
    todosNegocios: Array
})
</script>

<template>
    <div class="fixed inset-0 flex flex-col bg-gray-50 overflow-hidden">
        <Header />

        <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
            <Menucreador class="hidden md:flex" />

            <main class="flex-1 p-6 space-y-8 max-w-7xl mx-auto w-full overflow-y-auto">
            
            <!-- Sección: Todos los Negocios -->
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 mb-4 tracking-tight">🛍️ Todos los Negocios Registrados</h1>
                <div v-if="todosNegocios && todosNegocios.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <BusinessCard
                        v-for="negocio in todosNegocios"
                        :key="negocio.id"
                        :negocio="negocio"
                    />
                </div>
                <div v-else class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm text-gray-400">
                    <span class="text-4xl block mb-2">🏪</span>
                    <p class="text-sm font-semibold">No hay negocios registrados en el sistema todavía.</p>
                </div>
            </div>

        </main>
        </div>
    </div>
</template>
