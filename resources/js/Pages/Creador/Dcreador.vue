<template>
  <div class="fixed inset-0 flex flex-col bg-gray-100 overflow-hidden">
    <Header />
    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
      <Menucreador class="hidden md:flex" />
      <main class="flex-1 p-6 overflow-y-auto">
        <Xcear />
      </main>
    </div>
  </div>
</template>

<script setup>
import Menucreador from './Dcomponents/Menucreador.vue';
import Xcear from './Dcomponents/Xcear.vue';
import Header from '@/Pages/Creador/Dcomponents/CHeader.vue';
</script>
