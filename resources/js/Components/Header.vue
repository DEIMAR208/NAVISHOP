<template>
  <header class="sticky top-0 z-40 w-full bg-gradient-to-r from-[#5E1746] via-purple-800 to-cyan-600 backdrop-blur-md border-b border-[#5E1746] shadow-lg transition-all">
    <div class="flex items-center w-full h-16 md:h-20">
      
      <!-- Brand Logo & Name Section (Matches sidebar width: w-64) -->
      <div class="w-full md:w-64 flex-shrink-0 flex items-center justify-between px-4 md:px-6 h-full">
        <Link :href="route('usuario')" class="flex items-center gap-3 group h-full">
          <ApplicationLogo className="h-[60%] aspect-square rounded-xl shadow-sm" />
          <span class="text-xl md:text-2xl font-black tracking-tight text-white group-hover:text-purple-200 transition-colors">
            NAVISHOP
          </span>
        </Link>

        <!-- Mobile Menu Toggle Button -->
        <button
          @click="menuOpen = !menuOpen"
          class="md:hidden p-2 rounded-xl text-white hover:bg-white/20 hover:text-white focus:outline-none transition-colors border border-white/30"
          aria-label="Toggle Navigation"
        >
          <span v-if="!menuOpen" class="text-xl block leading-none">☰</span>
          <span v-else class="text-xl block leading-none">✕</span>
        </button>
      </div>

      <!-- Right Content Area (desktop only) -->
      <div class="hidden md:flex flex-1 items-center justify-end px-6 h-full ">

        <!-- Right Side: Search and Profile -->
        <div class="flex items-center gap-3">
          <!-- Profile removed -->
          
        </div>
      </div>

    </div>

    <!-- Mobile Navigation Drawer -->
    <transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div v-if="menuOpen" class="md:hidden border-t border-fuchsia-700 bg-fuchsia-800 px-4 py-3 space-y-1 shadow-inner">
        <Link
          v-for="item in menuItems"
          :key="item.name"
          :href="item.route.startsWith('/') ? item.route : route(item.route)"
          @click="menuOpen = false"
          :class="[
            isRouteActive(item.route)
              ? 'bg-white/20 text-white shadow-md'
              : 'text-white hover:bg-white/10 hover:text-white',
            'flex items-center px-4 py-2.5 rounded-xl text-base font-medium transition-all'
          ]"
        >
          <span class="mr-3 text-lg">{{ item.icon }}</span>
          <span class="capitalize">{{ item.name }}</span>
        </Link>

        <!-- User profile in Mobile Menu removed -->
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ApplicationLogo from './ApplicationLogo.vue'

const page = usePage()
const menuOpen = ref(false)

const isAuthenticated = computed(() => !!page.props.auth?.user)
const isCreador = computed(() => page.props.auth?.user?.role === 'creador')

const isCreadorPath = computed(() => {
  return page.url.startsWith('/creador') || (page.url === '/perfil' && isCreador.value)
})

const menuItems = computed(() => {
  if (isCreadorPath.value) {
    return [
      { name: 'Dashboard', icon: '🏠', route: 'creador.dashboard' },
      { name: 'video', icon: '🛠️', route: 'creador.videos' },
      { name: 'crear video', icon: '📹', route: 'creador.videos.crear' },
      { name: 'promociones', icon: '👥', route: 'creador.promociones' },
      { name: 'Catálogo', icon: '📁', route: 'creador.catalogo' },
      { name: 'crear promociones', icon: '📊', route: 'creador.crear-promociones' },
      { name: 'mi negocio', icon: '🏪', route: 'creador.minegocio' }
    ]
  }

  const items = [
    { name: 'casa', icon: '🏠', route: 'usuario' },
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
</script>