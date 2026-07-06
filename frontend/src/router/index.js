import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// Configuración opcional de NProgress
NProgress.configure({ showSpinner: false })

const routes = [
  {
    path: '/',
    name: 'auth',
    component: () => import('@/views/auth/AuthView.vue')
  },
  {
    path: '/',
    component: () => import('@/layouts/MainLayout.vue'),
    children: [
      {
        path: 'home',
        name: 'home',
        component: () => import('@/views/home/HomeView.vue'),
        meta: { requiresAuth: false } // Temporalmente deshabilitado para desarrollo
      },
      {
        path: 'search',
        name: 'search',
        component: () => import('@/views/search/SearchView.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: 'tutors',
        name: 'tutors',
        component: () => import('@/views/tutors/TutorsView.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: 'professors',
        name: 'professors',
        component: () => import('@/views/tutors/ProfessorsView.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: 'subjects',
        name: 'subjects',
        component: () => import('@/views/subjects/SubjectsView.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: 'chat',
        name: 'chat',
        component: () => import('@/views/chat/ChatView.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('@/views/profile/ProfileView.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: 'profile/:id',
        name: 'public-profile',
        component: () => import('@/views/profile/PublicProfileView.vue'),
        meta: { requiresAuth: false }
      },
      // Ejemplos de rutas para Tutor y Admin (Sprint 2)
      {
        path: 'advisories-management',
        name: 'advisories-management',
        component: () => import('@/views/home/HomeView.vue'), // Componente Placeholder
        meta: { requiresAuth: true, role: 'TUTOR' }
      },
      {
        path: 'admin/dashboard',
        name: 'admin-dashboard',
        component: () => import('@/views/home/HomeView.vue'), // Componente Placeholder
        meta: { requiresAuth: true, role: 'ADMIN' }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  NProgress.start()
  const authStore = useAuthStore()
  
  if (authStore.token && !authStore.isAuthenticated) {
    await authStore.fetchUser()
  }

  if (to.name === 'auth' && authStore.isAuthenticated) {
    next({ name: 'home' })
  } else if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'auth' })
  } else if (to.meta.role) {
    // Validar el rol específico
    if (to.meta.role === 'STUDENT' && !authStore.isStudent) {
      next({ name: 'home' })
    } else if (to.meta.role === 'TUTOR' && !authStore.isTutor) {
      next({ name: 'home' })
    } else if (to.meta.role === 'ADMIN' && !authStore.isAdmin) {
      next({ name: 'home' })
    } else {
      next()
    }
  } else {
    next()
  }
})

router.afterEach(() => {
  NProgress.done()
})

export default router
