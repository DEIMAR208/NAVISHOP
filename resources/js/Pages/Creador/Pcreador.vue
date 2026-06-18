<script setup>
import { Head } from '@inertiajs/vue3'
import Menucreador from './Dcomponents/Menucreador.vue'
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'

defineProps({
    mustVerifyEmail: {
        type: Boolean,
        default: false
    },
    status: {
        type: String,
        default: ''
    },
})
</script>

<template>
  <Head title="Mi Perfil Creador" />

  <div class="fixed inset-0 flex flex-col bg-gray-50 overflow-hidden">
    <Header />

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <!-- Sidebar del Creador -->
      <Menucreador class="hidden md:flex" />

      <!-- Área de Contenido Principal -->
      <main class="flex-1 p-6 overflow-y-auto">
      <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">
        
        <!-- Header Decorativo Premium del Creador -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-100/40 relative overflow-hidden">
          <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
          <div class="absolute right-20 -bottom-10 w-32 h-32 rounded-full bg-white/10 blur-lg"></div>

          <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/20">
              🏪
            </div>
            <div class="text-center sm:text-left">
              <span class="bg-blue-500/30 text-blue-100 text-[10px] font-bold px-2.5 py-1 rounded-full border border-blue-400/20 uppercase tracking-wider">
                Perfil del Creador
              </span>
              <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-2">
                Ajustes de Creador
              </h1>
              <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-xl font-medium">
                Gestiona la información de tu cuenta de creador de negocios y mantén tus accesos al día.
              </p>
            </div>
          </div>
        </div>

        <!-- Formularios de Modificación -->
        <div class="space-y-6">
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-150 shadow-sm transition-all hover:shadow-lg">
            <UpdateProfileInformationForm
              :must-verify-email="mustVerifyEmail"
              :status="status"
              class="max-w-xl"
            />
          </div>

          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-150 shadow-sm transition-all hover:shadow-lg">
            <UpdatePasswordForm class="max-w-xl" />
          </div>

          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-150 shadow-sm transition-all hover:shadow-lg border-red-100">
            <DeleteUserForm class="max-w-xl" />
          </div>
        </div>

      </div>
    </main>
    </div>
  </div>
</template>
