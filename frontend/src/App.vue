<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()

onMounted(() => {
  if (authStore.token) {
    authStore.fetchUser()
  }
})
</script>

<template>
  <div class="fixed bottom-4 right-4 z-[9999] bg-yellow-500/90 text-yellow-950 px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm border border-yellow-400 pointer-events-none">
    🚧 Sitio en desarrollo
  </div>
  <router-view />
</template>
