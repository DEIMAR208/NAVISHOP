<template>
  <aside class="w-full md:w-64 h-full flex-shrink-0 bg-white border-r border-gray-200 shadow-sm flex flex-col justify-between">
    
    <!-- Navigation Container -->
    <div class="flex-1 px-4 py-6">
      
      <!-- Section Title -->
      <div class="px-3 mb-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Navegación</p>
      </div>

      <!-- Navigation Links -->
      <nav class="space-y-1.5">
        <Link
          v-for="item in filteredMenuItems"
          :key="item.name"
          :href="item.route.startsWith('/') ? item.route : route(item.route)"
          :class="[
            isRouteActive(item.route)
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200 font-semibold'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium',
            'group flex items-center px-4 py-3 text-sm md:text-base rounded-xl w-full transition-all duration-200 active:scale-98'
          ]"
        >
          <span class="mr-3 text-lg md:text-xl filter drop-shadow-sm">{{ item.icon }}</span>
          <span class="capitalize">{{ item.name }}</span>
        </Link>
      </nav>
      
    </div>

    <!-- User Profile Footer / Login Button -->
    <div v-if="isAuthenticated" class="p-4 border-t border-gray-100 bg-gray-50/50">
      <Link :href="isCreador ? route('creador.perfil') : route('usuario.perfil')" class="flex items-center group w-full p-2 rounded-xl hover:bg-white border border-transparent hover:border-gray-200/60 shadow-sm transition-all duration-200 bg-white/40">
        <img
          class="h-9 w-9 rounded-full object-cover bg-gray-200 border border-gray-300"
          :src="userAvatar"
          alt="User profile"
        >
        <div class="ml-3 text-left">
          <p class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">
            {{ page.props.auth.user.name }}
          </p>
          <p class="text-xs font-medium text-gray-400 group-hover:text-gray-600 transition-colors">Ver perfil</p>
        </div>
      </Link>
    </div>
    <div v-else class="p-4 border-t border-gray-100 bg-gray-50/50">
      <Link :href="route('login')" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm">
        Iniciar Sesión
      </Link>
    </div>

  </aside>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const searchQuery = ref('')
const menuOpen = ref(false)

const isAuthenticated = computed(() => !!page.props.auth?.user)
const isCreador = computed(() => page.props.auth?.user?.role === 'creador')

const userAvatar = computed(() => {
  const user = page.props.auth?.user;
  if (!user || !user.foto_perfil) return '/iconos/pdefecto.png';
  if (user.foto_perfil.startsWith('http')) return user.foto_perfil;
  if (user.foto_perfil === 'iconos/pdefecto.png') return '/iconos/pdefecto.png';
  return '/storage/' + user.foto_perfil;
});

// Elementos del menú dinámicos
const menuItems = computed(() => {
  const items = [
    { name: 'inicio', icon: '🏠', route: 'usuario' },
    { name: 'Videos', icon: '👥', route: '/videos' },
    { name: 'Promociones', icon: '📁', route: '/promociones' },
  ]

  if (isCreador.value) {
    items.push({ name: 'Panel Creador', icon: '📊', route: 'creador.dashboard' })
  } else {
    items.push({ name: 'Ser creador', icon: '📊', route: '/fusuario' })
  }

  return items
})

// Buscador reactivo integrado (Filtrará dinámicamente según lo que se escriba)
const filteredMenuItems = computed(() => {
  return menuItems.value.filter(item =>
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Validación activa de rutas para Inertia/Ziggy
const isRouteActive = (itemRoute) => {
  const currentUrl = page.url.split('?')[0]

  if (itemRoute.startsWith('/')) {
    return currentUrl === itemRoute || currentUrl.startsWith(itemRoute + '/')
  }

  try {
    return route().current(itemRoute)
  } catch (e) {
    return false
  }
}

// Manejador de clics en el menú
const handleMenuClick = (item) => {
  if (!isAuthenticated.value) {
    router.visit(route('login'))
    return
  }

  menuOpen.value = false

  const targetRoute = item.route.startsWith('/') ? item.route : route(item.route)
  router.visit(targetRoute)
}
</script>
