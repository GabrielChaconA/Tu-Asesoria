<script setup>
import Navbar from '@/components/layout/Navbar.vue'
import Footer from '@/components/layout/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-background flex flex-col">
    <Navbar />
    <main class="flex-1 pb-16 md:pb-0 flex flex-col">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
    <Footer />
  </div>
</template>

<style>
/* Transiciones de página */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Estilos personalizados para NProgress usando colores de la marca */
#nprogress .bar {
  background: var(--primary) !important;
  height: 3px !important;
}
#nprogress .peg {
  box-shadow: 0 0 10px var(--primary), 0 0 5px var(--primary) !important;
}
</style>
