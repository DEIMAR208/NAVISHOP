<script setup>
defineProps({
  className: {
    type: String,
    default: ''
  }
})
</script>

<template>
  <div 
    :class="[
      'flex justify-center items-center rounded-xl bg-gradient-to-b from-[#0f63fe] to-[#60e0d9] transition-all duration-300 overflow-hidden shrink-0 p-2 md:p-3 shadow-sm',
      className || $attrs.class ? className : 'w-[60px] h-[90px]'
    ]"
  >
    <img 
      class="w-full h-full object-contain transition-all duration-300" 
      src="/iconos/gepeese.png"
      alt="Logo"
    >
  </div>
</template>
