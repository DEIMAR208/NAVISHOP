<script setup>
import Header from '../../Components/Header.vue';
import Menu from '../../Components/Menu.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Vusuario from './Vusuario.vue';
import Pusuario from './Pusuario.vue';
import Fusuario from './Fusuario.vue';
import Eiusuario from './Eiusuario.vue';
import BusinessCard from '../Creador/Dcomponents/BusinessCard.vue';

const props = defineProps({
    negocios: Array,
    mustVerifyEmail: {
        type: Boolean,
        default: false
    },
    status: {
        type: String,
        default: ''
    }
});

const page = usePage();
const currentRoute = computed(() => page.url);
const negocios = computed(() => props.negocios || []);

const renderContent = () => {
    if (currentRoute.value.includes('/videos')) return Vusuario;
    if (currentRoute.value.includes('/promociones')) return Pusuario;
    if (currentRoute.value.includes('/fusuario')) return Fusuario;
    if (currentRoute.value.includes('/perfil')) return Eiusuario;
    return null;
};

const showNegocios = () => {
    return currentRoute.value === '/usuario';
};
</script>

<template>
    <div class="fixed inset-0 flex flex-col bg-slate-50/50 overflow-hidden">
        <Header />

        <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
            <!-- Sidebar Menu -->
            <Menu class="hidden md:flex" />

            <!-- Main Content Area -->
            <div class="flex-1 p-4 md:p-6 max-w-7xl w-full mx-auto overflow-y-auto">
                <component :is="renderContent()" :must-verify-email="mustVerifyEmail" :status="status" />
                <div v-if="showNegocios()">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6 font-sans">Negocios Registrados</h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <BusinessCard 
                            v-for="negocio in negocios" 
                            :key="negocio.id" 
                            :negocio="negocio" 
                            :ruta-publica="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
