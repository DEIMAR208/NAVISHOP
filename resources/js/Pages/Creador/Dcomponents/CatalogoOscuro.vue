<template>
    <!-- Contenedor Principal (Tarjeta del Catálogo) -->
    <div class="w-[1000px] h-[450px] bg-[#161623] border-2 border-[#2d2d44] rounded-xl p-6 my-4 mx-auto flex flex-col justify-between shadow-xl">
        
        <!-- Encabezado del Catálogo -->
        <div class="w-full h-[80px] bg-[#222235] border border-[#2d2d44] rounded-xl px-6 flex items-center justify-between">
            <h1 class="text-xl font-bold text-white tracking-wide uppercase">Nombre del Catálogo</h1>
            <span class="text-xs bg-[#e94560] text-white px-3 py-1 rounded-full font-semibold">Burger King</span>
        </div>

        <!-- Contenedor de Productos (Fila Horizontal) -->
        <div class="flex justify-center gap-6 w-full mt-2">
            
            <!-- TARJETA PRODUCTO 1 -->
            <div class="w-[450px] h-[280px] bg-[#1f1f32] border border-[#2d2d44] rounded-xl p-4 flex flex-col justify-between transition-all hover:border-[#e94560] hover:scale-[1.01] duration-200">
                <!-- Contenedor de la Imagen del Producto -->
                <div class="w-full h-[170px] bg-[#161623] border border-[#2d2d44] rounded-lg flex items-center justify-center text-gray-500 overflow-hidden relative">
                    <!-- Aquí iría la etiqueta <img> en el futuro -->
                    <span class="text-sm font-medium">🖼️ [Imagen Producto 1]</span>
                    <span class="absolute top-2 left-2 bg-black/70 text-xs text-white px-2 py-0.5 rounded font-bold">¡Top!</span>
                </div>
                
                <!-- Detalles: Nombre y Precio -->
                <div class="flex justify-between items-center bg-[#161623] p-3 rounded-lg border border-[#2d2d44]">
                    <h2 class="text-white font-semibold text-base truncate max-w-[250px]">Nombre del Producto 1</h2>
                    <span class="text-[#1dd1a1] font-bold text-lg">Bs 42</span>
                </div>
            </div>

            <!-- TARJETA PRODUCTO 2 -->
            <div class="w-[450px] h-[280px] bg-[#1f1f32] border border-[#2d2d44] rounded-xl p-4 flex flex-col justify-between transition-all hover:border-[#e94560] hover:scale-[1.01] duration-200">
                <!-- Contenedor de la Imagen del Producto -->
                <div class="w-full h-[170px] bg-[#161623] border border-[#2d2d44] rounded-lg flex items-center justify-center text-gray-500 overflow-hidden">
                    <span class="text-sm font-medium">🖼️ [Imagen Producto 2]</span>
                </div>
                
                <!-- Detalles: Nombre y Precio -->
                <div class="flex justify-between items-center bg-[#161623] p-3 rounded-lg border border-[#2d2d44]">
                    <h2 class="text-white font-semibold text-base truncate max-w-[250px]">Nombre del Producto 2</h2>
                    <span class="text-[#1dd1a1] font-bold text-lg">Bs 35</span>
                </div>
            </div>

        </div>
    </div>
</template>
