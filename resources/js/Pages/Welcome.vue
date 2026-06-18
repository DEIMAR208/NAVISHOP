<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <!-- Contenedor principal que maneja el fondo y el overlay -->
  <div class="pantalla-inicio">
    <div class="gradient-overlay"></div>

    <!-- Botón Superior Derecha -->
    <div class="superior-derecha">
      <Link :href="route('usuario')" class="boton-login-link">
        Omitir
      </Link>
    </div>

    <!-- Contenido Central -->
    <div class="caja-grande">
      <!-- Contenedor del Logo con Efecto de Cristal -->
      <div class="caja-chica">
        <img class="logo-gps" src="/iconos/gepeese.png" alt="GPS Logo">
      </div>

      <!-- Título de la App -->
      <h1 class="titulo-app">
        NAVISHOP
      </h1>

      <!-- Botón de Entrada -->
      <Link :href="route('login')" class="boton-bienvenida">
        Inicio
      </Link>
    </div>
  </div>
</template>

<style scoped>
/* --- CONFIGURACIÓN DE LA PANTALLA Y FONDO --- */
.pantalla-inicio {
  margin: 0;
  color: white;
  font-family: 'Arial', sans-serif;
  height: 100vh;
  width: 100vw;
  overflow: hidden;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  /* Imagen de fondo */
  background-image: url('/iconos/cristo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* Gradiente superior para que no opaque la imagen pero mejore contraste */
.gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(180deg, rgba(135, 206, 235, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
  z-index: 1;
  pointer-events: none;
}

/* --- DISEÑO DE LOS ELEMENTOS --- */
.superior-derecha {
  position: absolute;
  top: 25px;
  right: 25px;
  z-index: 10;
}

.boton-login-link {
  color: white;
  text-decoration: none;
  font-weight: bold;
  padding: 10px 24px;
  border: 2px solid white;
  border-radius: 30px; /* Bordes más redondeados y modernos */
  transition: all 0.3s ease;
  backdrop-filter: blur(5px); /* Efecto cristal detrás del botón */
}

.boton-login-link:hover {
  background-color: white;
  color: #0f63fe;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.caja-grande {
  position: relative;
  z-index: 5;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  width: 90%;
  max-width: 500px;
}

/* Caja del logo mejorada con sombras suaves */
.caja-chica {
  width: 220px;
  height: 220px;
  background: linear-gradient(135deg, #0f63fe 0%, #60e0d9 100%);
  border-radius: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 15px 35px rgba(15, 99, 254, 0.4);
}

.logo-gps {
  width: 140px;
  height: auto;
  /* Eliminados los margins fijos para que quede perfectamente centrado mediante flex */
}

/* Título con gradiente limpio (reemplazando Tailwind para evitar conflictos) */
.titulo-app {
  margin-top: 25px;
  margin-bottom: 25px;
  font-size: 3.5rem;
  font-weight: 900;
  letter-spacing: -2px;
  text-transform: uppercase;
  background: linear-gradient(to right, #ffffff, #ff7b00);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.5));
}

/* Botón de bienvenida responsivo y con mejores efectos */
.boton-bienvenida {
  background: linear-gradient(135deg, #0f63fe 0%, #60e0d9 100%);
  border: none;
  width: 100%;
  max-width: 350px;
  padding: 16px 0;
  border-radius: 30px;
  color: white;
  font-size: 1.1rem;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  box-shadow: 0 8px 25px rgba(15, 99, 254, 0.3);
  transition: all 0.3s ease;
}

.boton-bienvenida:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(96, 224, 217, 0.5);
  filter: brightness(1.1);
}
</style>
