<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const fileInput = ref(null);
const photoPreview = ref(null);

const form = useForm({
    name: user.name,
    email: user.email,
    foto_perfil: null,
});

const userAvatar = computed(() => {
    if (photoPreview.value) return photoPreview.value;
    if (!user || !user.foto_perfil) return '/iconos/pdefecto.png';
    if (user.foto_perfil.startsWith('http')) return user.foto_perfil;
    if (user.foto_perfil === 'iconos/pdefecto.png') return '/iconos/pdefecto.png';
    return '/storage/' + user.foto_perfil;
});

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    form.foto_perfil = file;
    
    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PATCH',
    })).post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('foto_perfil');
            photoPreview.value = null;
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide">
                Información de Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Actualiza los datos personales, correo electrónico y foto de perfil de tu cuenta.
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
        >
            <!-- Foto de Perfil Interactiva -->
            <div class="flex flex-col sm:flex-row items-center gap-5 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div 
                    @click="triggerFileInput" 
                    class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-white shadow-md overflow-hidden group cursor-pointer bg-white transition-all hover:border-blue-500 hover:shadow-lg shrink-0"
                >
                    <img 
                        :src="userAvatar" 
                        alt="Avatar usuario" 
                        class="w-full h-full object-cover"
                    />
                    <!-- Overlay de cambio -->
                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <span class="text-white text-xl">📷</span>
                        <span class="text-white text-[9px] font-bold uppercase tracking-wider mt-1">Cambiar</span>
                    </div>
                </div>

                <div class="text-center sm:text-left space-y-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Foto de Perfil</span>
                    <button 
                        type="button" 
                        @click="triggerFileInput" 
                        class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors"
                    >
                        Seleccionar Imagen
                    </button>
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        accept="image/*" 
                        @change="handlePhotoChange" 
                    />
                    <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                        Formatos permitidos: JPG, PNG o WEBP. Máximo 2MB.
                    </p>
                    <InputError class="mt-1" :message="form.errors.foto_perfil" />
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Nombre" class="font-bold text-xs uppercase tracking-wider text-slate-400" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5 block w-full py-3 bg-slate-50 border border-slate-200 focus:bg-white text-slate-700"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Correo electrónico" class="font-bold text-xs uppercase tracking-wider text-slate-400" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5 block w-full py-3 bg-slate-50 border border-slate-200 focus:bg-white text-slate-700"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Tu dirección de correo no está verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Haz clic aquí para reenviar el correo de verificación.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    Se ha enviado un nuevo enlace de verificación a tu dirección de correo.
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton 
                    :disabled="form.processing"
                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-100 cursor-pointer text-xs uppercase tracking-wider transition-all"
                >
                    Guardar Cambios
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg"
                    >
                        ✓ Guardado correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
