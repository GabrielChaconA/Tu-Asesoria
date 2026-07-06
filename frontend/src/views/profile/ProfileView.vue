<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import EditProfileModal from '@/components/profile/EditProfileModal.vue'
import Button from '@/components/ui/Button.vue'
import Card from '@/components/ui/Card.vue'
import CardHeader from '@/components/ui/CardHeader.vue'
import CardTitle from '@/components/ui/CardTitle.vue'
import CardContent from '@/components/ui/CardContent.vue'
import Badge from '@/components/ui/Badge.vue'
import Tabs from '@/components/ui/Tabs.vue'
import TabsList from '@/components/ui/TabsList.vue'
import TabsTrigger from '@/components/ui/TabsTrigger.vue'
import TabsContent from '@/components/ui/TabsContent.vue'
import Avatar from '@/components/ui/Avatar.vue'
import AvatarFallback from '@/components/ui/AvatarFallback.vue'
import AvatarImage from '@/components/ui/AvatarImage.vue'
import { 
  Star, MapPin, Calendar, Award, FileText, MessageCircle, 
  Edit, Plus, GraduationCap, Briefcase, Clock, Users 
} from '@lucide/vue'

const router = useRouter()

const authStore = useAuthStore()

// Mapear los datos del usuario autenticado
const userProfile = computed(() => {
  const user = authStore.user
  if (!user) return {}
  
  return {
    id: user.id,
    name: `${user.name} ${user.lastname || ''}`.trim(),
    email: user.email,
    role: user.role === 'USER' && user.is_tutor ? 'Tutor' : user.role === 'USER' ? 'Student' : 'Admin',
    subject: "N/A", // Por implementar
    specialty: "N/A", // Por implementar
    university: user.university?.name || "Sin universidad asociada",
    location: "México", // Mock
    rating: 0, reviews: 0, students: 0, sessions: 0, // Mocks por ahora
    joinedDate: new Date(user.createdAt).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }),
    image: user.profileImageUrl || "https://ui-avatars.com/api/?name=" + encodeURIComponent(user.name) + "&background=random",
    bio: user.bio || "Aún no has escrito una biografía.",
    subjects: [], // Por implementar
    languages: ["Spanish"], // Mock
    hourlyRate: 0, availability: "N/A",
  }
})

const isEditModalOpen = ref(false)

const achievements = [
  { id: 1, title: "PhD in Applied Mathematics", institution: "UNAM", year: "2015", type: "degree" },
  { id: 2, title: "Master of Science in Mathematics", institution: "UNAM", year: "2012", type: "degree" },
  { id: 3, title: "Excellence in Teaching Award", institution: "Faculty of Sciences, UNAM", year: "2022", type: "award" },
  { id: 4, title: "Research Grant - CONACYT", institution: "National Council of Science and Technology", year: "2020", type: "award" },
  { id: 5, title: "Certified Online Educator", institution: "Coursera", year: "2021", type: "certificate" },
]

const reviews = [
  { id: 1, author: "Carlos M.", rating: 5, date: "2 weeks ago", text: "Excellent tutor! Dr. González explained calculus concepts in a way I could finally understand. Highly recommended for anyone struggling with math.", image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" },
  { id: 2, author: "Ana S.", rating: 5, date: "1 month ago", text: "Very patient and knowledgeable. She helped me prepare for my final exam and I got an A! Thank you so much.", image: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face" },
  { id: 3, author: "Roberto L.", rating: 4, date: "2 months ago", text: "Great explanations and very organized sessions. Would definitely recommend to other engineering students.", image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" },
]

const isOwnProfile = ref(true)
</script>

<template>
  <div class="min-h-screen bg-background pb-16 md:pb-0">
    <div class="mx-auto max-w-5xl px-4 py-8">
      <!-- Profile Header -->
      <Card class="mb-8 overflow-hidden">
        <div class="h-32 bg-accent" />
        <CardContent class="relative px-6 pb-6">
          <div class="flex flex-col items-start gap-6 sm:flex-row">
            <Avatar class="-mt-16 h-32 w-32 border-4 border-card">
              <AvatarImage :src="userProfile.image" :alt="userProfile.name" />
              <AvatarFallback class="text-3xl">
                {{ userProfile.name.split(' ').map(n => n[0]).join('') }}
              </AvatarFallback>
            </Avatar>

            <div class="flex-1 pt-2 sm:pt-4">
              <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                  <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold">{{ userProfile.name }}</h1>
                    <Badge class="bg-primary text-primary-foreground">{{ userProfile.role }}</Badge>
                  </div>
                  <p class="mt-1 text-muted-foreground">{{ userProfile.specialty }} - {{ userProfile.subject }}</p>
                  <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
                    <span class="flex items-center gap-1">
                      <MapPin class="h-4 w-4" />
                      {{ userProfile.university }}
                    </span>
                    <span class="flex items-center gap-1">
                      <Calendar class="h-4 w-4" />
                      Joined {{ userProfile.joinedDate }}
                    </span>
                  </div>
                </div>

                <div class="flex gap-2">
                  <template v-if="isOwnProfile">
                    <Button variant="outline" @click="isEditModalOpen = true">
                      <Edit class="mr-2 h-4 w-4" />
                      Edit Profile
                    </Button>
                  </template>
                  <template v-else>
                    <router-link :to="`/chat?tutor=${userProfile.id}`">
                      <Button>
                        <MessageCircle class="mr-2 h-4 w-4" />
                        Message
                      </Button>
                    </router-link>
                  </template>
                </div>
              </div>

              <!-- Stats -->
              <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-lg bg-muted p-4 text-center">
                  <div class="flex items-center justify-center gap-1 text-2xl font-bold">
                    <Star class="h-5 w-5 fill-primary text-primary" />
                    {{ userProfile.rating }}
                  </div>
                  <p class="text-xs text-muted-foreground">{{ userProfile.reviews }} reviews</p>
                </div>
                <div class="rounded-lg bg-muted p-4 text-center">
                  <div class="flex items-center justify-center gap-1 text-2xl font-bold">
                    <Users class="h-5 w-5 text-primary" />
                    {{ userProfile.students }}
                  </div>
                  <p class="text-xs text-muted-foreground">Students</p>
                </div>
                <div class="rounded-lg bg-muted p-4 text-center">
                  <div class="flex items-center justify-center gap-1 text-2xl font-bold">
                    <Briefcase class="h-5 w-5 text-primary" />
                    {{ userProfile.sessions }}
                  </div>
                  <p class="text-xs text-muted-foreground">Sessions</p>
                </div>
                <div class="rounded-lg bg-muted p-4 text-center">
                  <div class="text-2xl font-bold">${{ userProfile.hourlyRate }}</div>
                  <p class="text-xs text-muted-foreground">Per hour</p>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Tabs Section -->
      <Tabs default-value="about" class="space-y-6">
        <TabsList class="w-full justify-start overflow-x-auto overflow-y-hidden">
          <TabsTrigger value="about">About</TabsTrigger>
          <TabsTrigger value="achievements">Achievements</TabsTrigger>
          <TabsTrigger value="reviews">Reviews</TabsTrigger>
        </TabsList>

        <TabsContent value="about" class="space-y-6">
          <!-- Biography -->
          <Card>
            <CardHeader>
              <CardTitle>Biography</CardTitle>
            </CardHeader>
            <CardContent>
              <p class="text-muted-foreground leading-relaxed">{{ userProfile.bio }}</p>
            </CardContent>
          </Card>

          <!-- Subjects & Skills -->
          <Card>
            <CardHeader>
              <CardTitle>Subjects & Expertise</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="flex flex-wrap gap-2">
                <Badge v-for="subject in userProfile.subjects" :key="subject" variant="secondary" class="px-3 py-1">
                  {{ subject }}
                </Badge>
              </div>
            </CardContent>
          </Card>

          <!-- Availability -->
          <Card>
            <CardHeader>
              <CardTitle>Availability</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="flex items-center gap-2 text-muted-foreground">
                <Clock class="h-5 w-5 text-primary" />
                <span>{{ userProfile.availability }}</span>
              </div>
              <div class="mt-4 flex flex-wrap gap-2">
                <Badge v-for="lang in userProfile.languages" :key="lang" variant="outline">
                  {{ lang }}
                </Badge>
              </div>
            </CardContent>
          </Card>

          <!-- Become a Tutor CTA -->
          <Card v-if="isOwnProfile && userProfile.role === 'Student'" class="bg-primary/5 border-primary/20">
            <CardContent class="p-6">
              <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                  <GraduationCap class="h-8 w-8 text-primary" />
                </div>
                <div class="flex-1">
                  <h3 class="text-lg font-semibold">Ready to Share Your Knowledge?</h3>
                  <p class="text-muted-foreground">
                    Become a tutor and help other students succeed while earning money.
                  </p>
                </div>
                <Button size="lg">
                  Become a Tutor
                </Button>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="achievements" class="space-y-6">
          <!-- Add Achievement Button -->
          <div v-if="isOwnProfile" class="flex justify-end">
            <Button>
              <Plus class="mr-2 h-4 w-4" />
              Add Achievement
            </Button>
          </div>

          <!-- Achievements Grid -->
          <div class="grid gap-4 sm:grid-cols-2">
            <Card v-for="achievement in achievements" :key="achievement.id" class="group">
              <CardContent class="p-6">
                <div class="flex items-start gap-4">
                  <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10">
                    <GraduationCap v-if="achievement.type === 'degree'" class="h-6 w-6 text-primary" />
                    <Award v-else-if="achievement.type === 'award'" class="h-6 w-6 text-primary" />
                    <FileText v-else-if="achievement.type === 'certificate'" class="h-6 w-6 text-primary" />
                  </div>
                  <div>
                    <h3 class="font-semibold">{{ achievement.title }}</h3>
                    <p class="text-sm text-muted-foreground">{{ achievement.institution }}</p>
                    <Badge variant="secondary" class="mt-2">
                      {{ achievement.year }}
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        <TabsContent value="reviews" class="space-y-6">
          <!-- Reviews Summary -->
          <Card>
            <CardContent class="p-6">
              <div class="flex flex-col items-center gap-6 sm:flex-row">
                <div class="text-center">
                  <div class="text-5xl font-bold text-primary">{{ userProfile.rating }}</div>
                  <div class="mt-1 flex items-center justify-center">
                    <Star
                      v-for="star in 5"
                      :key="star"
                      :class="['h-5 w-5', star <= Math.round(userProfile.rating) ? 'fill-primary text-primary' : 'text-muted']"
                    />
                  </div>
                  <p class="mt-1 text-sm text-muted-foreground">
                    Based on {{ userProfile.reviews }} reviews
                  </p>
                </div>
                <div class="flex-1 space-y-2">
                  <div v-for="rating in [5, 4, 3, 2, 1]" :key="rating" class="flex items-center gap-2">
                    <span class="w-3 text-sm">{{ rating }}</span>
                    <Star class="h-4 w-4 fill-primary text-primary" />
                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-muted">
                      <div
                        class="h-full bg-primary transition-all"
                        :style="{ width: `${rating === 5 ? 85 : rating === 4 ? 12 : rating === 3 ? 3 : 0}%` }"
                      />
                    </div>
                    <span class="w-10 text-right text-sm text-muted-foreground">
                      {{ rating === 5 ? 85 : rating === 4 ? 12 : rating === 3 ? 3 : 0 }}%
                    </span>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Individual Reviews -->
          <div class="space-y-4">
            <Card v-for="review in reviews" :key="review.id">
              <CardContent class="p-6">
                <div class="flex items-start gap-4">
                  <Avatar>
                    <AvatarImage :src="review.image" :alt="review.author" />
                    <AvatarFallback>{{ review.author[0] }}</AvatarFallback>
                  </Avatar>
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <h4 class="font-semibold">{{ review.author }}</h4>
                      <span class="text-sm text-muted-foreground">{{ review.date }}</span>
                    </div>
                    <div class="mt-1 flex items-center">
                      <Star
                        v-for="star in 5"
                        :key="star"
                        :class="['h-4 w-4', star <= review.rating ? 'fill-primary text-primary' : 'text-muted']"
                      />
                    </div>
                    <p class="mt-3 text-muted-foreground">{{ review.text }}</p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>
      </Tabs>

      <!-- Edit Profile Modal -->
      <EditProfileModal 
        :is-open="isEditModalOpen" 
        :user="authStore.user" 
        @close="isEditModalOpen = false" 
      />
    </div>
  </div>
</template>
