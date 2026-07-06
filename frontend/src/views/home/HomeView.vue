<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Card from '@/components/ui/Card.vue'
import CardHeader from '@/components/ui/CardHeader.vue'
import CardTitle from '@/components/ui/CardTitle.vue'
import CardDescription from '@/components/ui/CardDescription.vue'
import CardContent from '@/components/ui/CardContent.vue'
import Badge from '@/components/ui/Badge.vue'
import Select from '@/components/ui/Select.vue'
import SelectTrigger from '@/components/ui/SelectTrigger.vue'
import SelectValue from '@/components/ui/SelectValue.vue'
import SelectContent from '@/components/ui/SelectContent.vue'
import SelectItem from '@/components/ui/SelectItem.vue'
import api from '@/services/api'
import { Search, Star, Users, Calculator, Code, Atom, PiSquare, Database, Cpu, ChevronRight, BookOpen, ArrowLeft, MessageSquare, User as UserIcon } from '@lucide/vue'
import { useCacheStore } from '@/stores/cacheStore'

const router = useRouter()
const cacheStore = useCacheStore()

const categories = ref([])
const loading = ref(true)
const error = ref(null)

// Modal State
const isModalOpen = ref(false)
const selectedSubjectTitle = ref('')
const modalTutors = ref([])
const isLoadingTutors = ref(false)
const selectedTutor = ref(null)

import { onMounted } from 'vue'

const openTutorsModal = async (category) => {
  isModalOpen.value = true
  selectedTutor.value = null
  selectedSubjectTitle.value = category.name
  isLoadingTutors.value = true
  modalTutors.value = []
  
  try {
    const response = await api.get(`/subjects/${category.id}/tutors`)
    modalTutors.value = response.data.data
  } catch (err) {
    console.error('Failed to load tutors', err)
  } finally {
    isLoadingTutors.value = false
  }
}

const fetchSubjects = async () => {
  try {
    loading.value = true
    categories.value = await cacheStore.getSubjectsAll()
  } catch (err) {
    error.value = 'Failed to load subjects. Please try again later.'
  } finally {
    loading.value = false
  }
}

const fetchFeaturedTutors = async () => {
  try {
    featuredTutors.value = await cacheStore.getFeaturedTutors()
  } catch (err) {
    console.error('Failed to load featured tutors', err)
  }
}

onMounted(() => {
  fetchSubjects()
  fetchFeaturedTutors()
})

const universities = [
  "Instituto Tecnológico de Morelia", "UNAM", "IPN", "UDG", "Tec de Monterrey", "UAM", "BUAP", "UV"
]

const featuredTutors = ref([])

const searchQuery = ref("")
const selectedUniversity = ref("")
</script>

<template>
  <div class="min-h-screen bg-background pb-16 md:pb-0">
    <!-- Hero Section -->
    <section class="relative bg-zinc-950 px-4 py-20 sm:py-32">
      <!-- Minimalist Dark Background Gradient -->
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/15 via-zinc-950 to-zinc-950"></div>
      
      <div class="relative mx-auto max-w-4xl text-center">
        <h1 class="mb-6 text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl text-white text-balance">
          Encuentra a tu Tutor Ideal
        </h1>
        <p class="mb-12 text-lg text-zinc-400 sm:text-xl max-w-2xl mx-auto text-balance">
          Conecta con tutores verificados de las mejores universidades y domina tus materias.
        </p>
        
        <!-- Modern Search Bar Pill (Dark Theme) -->
        <div class="mx-auto flex max-w-3xl flex-col sm:flex-row items-center gap-2 rounded-3xl bg-zinc-900 p-2 shadow-2xl shadow-primary/10 border border-white/10">
          <div class="relative flex-1 w-full">
            <Search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400" />
            <Input
              type="text"
              placeholder="Buscar universidad, tutor o materia..."
              v-model="searchQuery"
              class="h-14 border-0 bg-transparent pl-12 text-base text-white placeholder:text-zinc-500 focus-visible:ring-0 focus-visible:ring-offset-0 shadow-none"
            />
          </div>
          
          <div class="h-8 w-px bg-white/10 hidden sm:block"></div>
          
          <div class="w-full sm:w-64">
            <Select v-model="selectedUniversity">
              <SelectTrigger class="h-14 border-0 bg-transparent text-white focus:ring-0 focus:ring-offset-0 shadow-none text-base">
                <SelectValue placeholder="Seleccionar universidad" />
              </SelectTrigger>
              <SelectContent class="bg-zinc-900 border-white/10 text-white">
                <SelectItem v-for="uni in universities" :key="uni" :value="uni" class="focus:bg-zinc-800 focus:text-white">
                  {{ uni }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
          
          <Button size="lg" class="h-14 w-full sm:w-auto rounded-2xl px-8 bg-primary text-primary-foreground hover:bg-primary/90" @click="router.push('/search')">
            <Search class="mr-2 h-5 w-5" />
            Buscar
          </Button>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold sm:text-3xl">Explorar por Materia</h2>
            <p class="mt-1 text-muted-foreground">Encuentra tutores en tu área de estudio</p>
          </div>
          <router-link to="/subjects">
            <Button variant="outline" class="hidden sm:flex">
              Ver Todo
              <ChevronRight class="ml-1 h-4 w-4" />
            </Button>
          </router-link>
        </div>
        
        <div v-if="loading" class="py-12 text-center text-muted-foreground">
          <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-primary border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
          <p class="mt-4">Cargando materias...</p>
        </div>
        
        <div v-else-if="error" class="py-12 text-center text-red-500">
          <p>{{ error }}</p>
        </div>
        
        <div v-else-if="categories.length === 0" class="py-12 text-center text-muted-foreground">
          <p>No se encontraron materias registradas.</p>
        </div>

        <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <Card v-for="category in categories.slice(0, 6)" :key="category.id" class="group cursor-pointer transition-all hover:shadow-lg hover:border-primary/50">
            <CardHeader>
              <div class="flex items-start justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 group-hover:bg-primary/20 transition-colors">
                  <component :is="BookOpen" class="h-6 w-6 text-primary" />
                </div>
                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                  <Star class="h-4 w-4 fill-primary text-primary" />
                  {{ category.average_rating ? category.average_rating + ' ★' : 'No ratings' }}
                </div>
              </div>
              <CardTitle class="mt-4">{{ category.name }}</CardTitle>
              <CardDescription class="line-clamp-2">{{ category.description || 'Sin descripción' }}</CardDescription>
            </CardHeader>
            <CardContent>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                  <Users class="h-4 w-4" />
                  {{ category.tutors_count === 1 ? '1 Tutor' : `${category.tutors_count} Tutores` }}
                </div>
                <Button size="sm" @click.stop="openTutorsModal(category)">
                  Ver Tutores
                  <ChevronRight class="ml-1 h-4 w-4" />
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
        
        <div class="mt-6 text-center sm:hidden">
          <router-link to="/subjects">
            <Button variant="outline">
              Ver Todas las Categorías
              <ChevronRight class="ml-1 h-4 w-4" />
            </Button>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Featured Tutors Section -->
    <section class="bg-muted/50 px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-7xl">
        <div class="mb-8">
          <h2 class="text-2xl font-bold sm:text-3xl">Tutores Destacados</h2>
          <p class="mt-1 text-muted-foreground">Los tutores mejor calificados de este mes</p>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <Card v-for="tutor in featuredTutors" :key="tutor.id" class="group cursor-pointer transition-all hover:shadow-lg">
            <CardContent class="p-6">
              <div class="flex items-start gap-4">
                <img
                  :src="tutor.image"
                  :alt="tutor.name"
                  class="h-16 w-16 rounded-full object-cover ring-2 ring-primary/20"
                />
                <div class="flex-1">
                  <h3 class="font-semibold">{{ tutor.name }}</h3>
                  <p class="text-sm text-muted-foreground">{{ tutor.subject }}</p>
                  <p class="text-xs text-muted-foreground">{{ tutor.university }}</p>
                </div>
              </div>
              <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-1">
                  <Star class="h-4 w-4 fill-primary text-primary" />
                  <span class="font-medium">{{ tutor.rating }}</span>
                  <span class="text-sm text-muted-foreground">({{ tutor.reviews }} reseñas)</span>
                </div>
                <router-link :to="`/profile/${tutor.id}`">
                  <Button size="sm" variant="outline">
                    Ver Perfil
                  </Button>
                </router-link>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-4xl">
        <Card class="bg-accent text-accent-foreground border-0">
          <CardContent class="p-8 text-center sm:p-12">
            <h2 class="mb-4 text-2xl font-bold sm:text-3xl text-balance">
              ¿Listo para Compartir tus Conocimientos?
            </h2>
            <p class="mb-6 text-accent-foreground/80">
              Únete a nuestra comunidad de tutores y ayuda a los estudiantes a tener éxito en su viaje académico.
            </p>
            <router-link to="/profile">
              <Button size="lg" variant="secondary" class="bg-primary text-primary-foreground hover:bg-primary/90">
                Conviértete en Tutor
                <ChevronRight class="ml-2 h-5 w-5" />
              </Button>
            </router-link>
          </CardContent>
        </Card>
      </div>
    </section>



    <!-- Modal de Tutores -->
    <div v-if="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
      <div class="bg-zinc-950 border border-white/10 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-zinc-900/50">
          <div class="flex items-center gap-3">
            <button v-if="selectedTutor" @click="selectedTutor = null" class="text-zinc-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-white/10 mr-1">
              <ArrowLeft class="h-5 w-5" />
            </button>
            <h3 class="text-xl font-semibold text-white">
              {{ selectedTutor ? 'Perfil del Maestro' : `Tutores de ${selectedSubjectTitle}` }}
            </h3>
          </div>
          <button @click="isModalOpen = false; selectedTutor = null" class="text-zinc-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1">
          <div v-if="isLoadingTutors" class="py-8 text-center text-muted-foreground flex flex-col items-center">
            <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-r-transparent mb-4"></div>
            <p>Cargando tutores...</p>
          </div>
          
          <div v-else-if="modalTutors.length === 0" class="py-8 text-center text-muted-foreground">
            <p>Aún no hay tutores registrados para esta materia.</p>
          </div>
          
          <!-- Tutor Detail View -->
          <div v-else-if="selectedTutor" class="flex flex-col gap-6 animate-in fade-in zoom-in-95 duration-200 pb-4">
            <div class="flex flex-col items-center text-center mt-2">
              <div class="h-24 w-24 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-3xl uppercase mb-4 shadow-lg border border-primary/20">
                {{ selectedTutor.name.charAt(0) }}
              </div>
              <h4 class="text-2xl font-bold text-white">{{ selectedTutor.name }} {{ selectedTutor.lastname }}</h4>
              <p class="text-primary font-medium mt-1">Profesor Especializado</p>
              
              <div class="flex items-center gap-2 mt-4 bg-zinc-900 px-4 py-2 rounded-full border border-white/10">
                <Star class="h-5 w-5 fill-primary text-primary" />
                <span class="font-bold text-white">{{ selectedTutor.tutor_reviews_avg_rating ? parseFloat(selectedTutor.tutor_reviews_avg_rating).toFixed(1) : 'Nuevo' }}</span>
                <span class="text-muted-foreground text-sm ml-1">Calificación Promedio</span>
              </div>
            </div>
            
            <div class="bg-zinc-900/50 rounded-xl p-5 border border-white/5">
              <h5 class="text-sm font-semibold text-zinc-400 uppercase tracking-wider mb-2">Descripción y Grado</h5>
              <p class="text-white/90 leading-relaxed text-sm">
                {{ selectedTutor.bio || 'Este maestro aún no ha escrito una descripción sobre su experiencia, metodología de enseñanza o grado académico. Sin embargo, ha sido verificado por TÚ ASESORÍA.' }}
              </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 mt-2">
              <Button class="flex-1" @click="router.push(`/chat?tutor=${selectedTutor.id}`); isModalOpen = false; selectedTutor = null">
                <MessageSquare class="mr-2 h-4 w-4" />
                Mandar Mensaje
              </Button>
              <Button variant="outline" class="flex-1" @click="router.push(`/profile/${selectedTutor.id}`); isModalOpen = false; selectedTutor = null">
                <UserIcon class="mr-2 h-4 w-4" />
                Ver Perfil
              </Button>
            </div>
          </div>
          
          <!-- Tutors List View -->
          <div v-else class="space-y-4">
            <div v-for="tutor in modalTutors" :key="tutor.id" @click="selectedTutor = tutor" class="flex items-center justify-between p-4 rounded-xl bg-zinc-900/50 border border-white/5 hover:bg-zinc-900 hover:border-primary/50 transition-all cursor-pointer group">
              <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-lg uppercase transition-transform group-hover:scale-110">
                  {{ tutor.name.charAt(0) }}
                </div>
                <div>
                  <p class="font-semibold text-white text-base">{{ tutor.name }} {{ tutor.lastname }}</p>
                  <p class="text-xs text-muted-foreground mt-0.5">Ver detalles del perfil</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 bg-zinc-950 px-3 py-1.5 rounded-lg border border-white/5">
                  <Star class="h-4 w-4 fill-primary text-primary" />
                  <span class="text-sm font-medium text-white">{{ tutor.tutor_reviews_avg_rating ? parseFloat(tutor.tutor_reviews_avg_rating).toFixed(1) : 'Nuevo' }}</span>
                </div>
                <ChevronRight class="h-5 w-5 text-muted-foreground group-hover:text-white transition-colors" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
