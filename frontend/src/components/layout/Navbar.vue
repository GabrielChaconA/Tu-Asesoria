<script setup>
import { useRoute } from 'vue-router'
import { Home, Search, MessageCircle, User, LogOut, Shield, Briefcase } from '@lucide/vue'
import { cn } from '@/lib/utils'
import { useAuthStore } from '@/stores/authStore'
import { computed } from 'vue'

const route = useRoute()
const authStore = useAuthStore()

const navItems = computed(() => {
  const items = []
  
  if (authStore.isAdmin) {
    items.push({ href: "/admin/validate-tutors", label: "Validar Tutores", icon: Shield })
    items.push({ href: "/admin/dashboard", label: "Panel Administrativo", icon: User })
    return items
  }

  items.push({ href: "/home", label: "Home", icon: Home })
  items.push({ href: "/search", label: "Explorar", icon: Search })
  
  if (authStore.isStudent) {
    // Other student specific routes
  }

  items.push({ href: "/chat", label: "Messages", icon: MessageCircle })
  items.push({ href: "/profile", label: "Profile", icon: User })

  if (authStore.isTutor) {
    items.push({ href: "/advisories-management", label: "Asesorías", icon: Briefcase })
  }

  return items
})
</script>

<template>
  <nav class="sticky top-0 z-50 w-full bg-zinc-950 border-b border-white/10">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <router-link to="/home" class="flex items-center gap-3 transition-transform hover:scale-105">
        <img src="/logo.png" alt="TÚ ASESORÍA Logo" class="h-12 w-auto rounded-md bg-white p-1" />
        <span class="text-2xl font-bold tracking-tight text-white">TÚ ASESORÍA</span>
      </router-link>

      <!-- Desktop Navigation -->
      <div class="hidden items-center gap-8 md:flex">
        <router-link
          v-for="item in navItems"
          :key="item.href"
          :to="item.href"
          :class="cn(
            'text-base font-medium transition-colors hover:text-primary',
            route.path === item.href
              ? 'text-primary'
              : 'text-zinc-400'
          )"
        >
          {{ item.label }}
        </router-link>
      </div>

      <div class="flex items-center gap-4">
        <button
          @click="authStore.logout()"
          class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-colors bg-white/10 text-white hover:bg-white/20 cursor-pointer"
        >
          <LogOut class="h-4 w-4" />
          <span class="hidden sm:inline">Logout</span>
        </button>
      </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50 flex items-center justify-around border-t border-border bg-background/95 backdrop-blur py-2 md:hidden">
      <router-link
        v-for="item in navItems"
        :key="item.href"
        :to="item.href"
        :class="cn(
          'flex flex-col items-center gap-1 rounded-lg px-3 py-1.5 text-[10px] font-medium transition-colors',
          route.path === item.href
            ? 'text-primary'
            : 'text-muted-foreground hover:text-foreground'
        )"
      >
        <component :is="item.icon" class="h-5 w-5" />
        {{ item.label }}
      </router-link>
    </div>
  </nav>
</template>
