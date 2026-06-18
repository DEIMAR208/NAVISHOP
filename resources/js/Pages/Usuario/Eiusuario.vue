<script setup>
import { Head } from '@inertiajs/vue3'
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
  <Head title="Mi Perfil" />

  <div class="space-y-8 max-w-4xl mx-auto py-4 animate-fade-in">
    <!-- Header Decorativo Premium -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-100/40 relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
      <div class="absolute right-20 -bottom-10 w-32 h-32 rounded-full bg-white/10 blur-lg"></div>

      <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/20">
          👤
        </div>
        <div class="text-center sm:text-left">
          <span class="bg-blue-500/30 text-blue-100 text-[10px] font-bold px-2.5 py-1 rounded-full border border-blue-400/20 uppercase tracking-wider">
            Ajustes de Cuenta
          </span>
          <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-2">
            Configuración de Perfil
          </h1>
          <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-xl font-medium">
            Actualiza tus datos personales, contraseña y gestiona la seguridad de tu cuenta.
          </p>
        </div>
      </div>
    </div>

    <!-- Secciones de Ajustes -->
    <div class="space-y-6">
      <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-xl"
        />
      </div>

      <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md border-red-100">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </div>
</template>
