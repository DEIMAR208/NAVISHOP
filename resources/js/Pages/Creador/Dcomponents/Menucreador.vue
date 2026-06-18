<template>
  <aside class="w-full md:w-64 h-full flex-shrink-0 bg-white border-r border-gray-200 shadow-sm flex flex-col justify-between">

      <header class="flex items-center justify-between h-auto px-4 md:px-6 py-4 md:py-10 md:mt-4 md:hidden">
        <Link :href="route('usuario')" class="flex items-center gap-3 group">
          <ApplicationLogo className="w-12 h-12 md:w-16 md:h-16 rounded-xl shadow-sm" />
          <span class="text-xl md:text-2xl font-black tracking-tight text-gray-900 group-hover:text-blue-600 transition-colors">
            NAVISHOP
          </span>
        </Link>
        <button
          @click="menuOpen = !menuOpen"
          class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100"
        >
          <span v-if="!menuOpen" class="text-2xl">☰</span>
          <span v-else class="text-2xl">✕</span>
        </button>
      </header>

      <nav
        :class="[
          'flex-1 px-4 space-y-1',
          menuOpen ? 'block' : 'hidden md:block'
        ]"
      >

        <button
          v-for="item in menuItems"
          :key="item.name"
          @click="handleMenuClick(item)"
          :class="[
            isRouteActive(item.route)
              ? 'bg-blue-600 text-white'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'group flex items-center px-3 py-2 text-sm md:text-base font-medium rounded-md w-full transition-colors'
          ]"
        >
          <span class="mr-3 text-lg md:text-xl">{{ item.icon }}</span>
          <span>{{ item.name }}</span>
        </button>

        <div class="mt-6 md:mt-10 hidden md:block">
          <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Your teams
          </h3>
          <div class="mt-2 space-y-1">
            <button
              v-for="team in teams"
              :key="team.name"
              class="group flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 w-full"
            >
              <span class="flex items-center justify-center w-6 h-6 mr-3 text-[10px] font-bold border rounded-lg bg-white uppercase text-gray-400">
                {{ team.initial }}
              </span>
              {{ team.name }}
            </button>
          </div>
        </div>
      </nav>

      <div
        v-if="isAuthenticated"
        :class="[
          'flex-shrink-0 p-4 border-t border-gray-200',
          menuOpen ? 'block' : 'hidden md:block'
        ]"
      >
        <Link :href="route('creador.perfil')" class="flex items-center group w-full p-1 rounded-lg hover:bg-gray-100/80 transition-all">
          <img
            class="inline-block h-9 w-9 rounded-full object-cover bg-gray-200 border border-gray-300"
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
      <div
        v-else
        :class="[
          'flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50/50',
          menuOpen ? 'block' : 'hidden md:block'
        ]"
      >
        <Link :href="route('login')" class="flex items-center justify-center w-full px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
          Iniciar Sesión
        </Link>
      </div>
    </aside>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

const page = usePage()
const menuOpen = ref(false)

const isAuthenticated = computed(() => !!page.props.auth?.user)

const userAvatar = computed(() => {
  const user = page.props.auth?.user;
  if (!user || !user.foto_perfil) return '/iconos/pdefecto.png';
  if (user.foto_perfil.startsWith('http')) return user.foto_perfil;
  if (user.foto_perfil === 'iconos/pdefecto.png') return '/iconos/pdefecto.png';
  return '/storage/' + user.foto_perfil;
});

const menuItems = [
  { name: 'inicio', icon: '🏠', route: 'creador.dashboard' },
  { name: 'video', icon: '🛠️', route: 'creador.videos' },
  { name: 'promociones', icon: '👥', route: 'creador.promociones' }, 
  { name: 'crear video', icon: '📹', route: 'creador.videos.crear' },
  { name: 'Catálogo', icon: '📁', route: 'creador.catalogo' },
  { name: 'crear promociones', icon: '📊', route: 'creador.crear-promociones' },
  { name : 'mi negocio', icon: '🏪', route: 'creador.minegocio' },
]

// Función inteligente para comprobar si el botón coincide con la ruta actual del navegador
const isRouteActive = (itemRoute) => {
  if (itemRoute === '#') return false;

  // Si la ruta usa la función global route('nombre') de Ziggy, comprobamos si está activa
  try {
    return route().current(itemRoute);
  } catch (e) {
    // Alternativa en caso de que pases una url directa tipo "/videos"
    return page.url.startsWith(itemRoute);
  }
}

const handleMenuClick = (item) => {
  if (!isAuthenticated.value) {
    router.visit(route('login'))
    return
  }

  menuOpen.value = false

  if (item.route === '#') {
    console.warn("Ruta no configurada aún");
    return;
  }

  const targetRoute = item.route.startsWith('/') ? item.route : route(item.route);
  router.visit(targetRoute);
}
</script>
