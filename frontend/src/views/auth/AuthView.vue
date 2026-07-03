<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Card from '@/components/ui/Card.vue'
import CardHeader from '@/components/ui/CardHeader.vue'
import CardTitle from '@/components/ui/CardTitle.vue'
import CardDescription from '@/components/ui/CardDescription.vue'
import CardContent from '@/components/ui/CardContent.vue'
import Label from '@/components/ui/Label.vue'
import Select from '@/components/ui/Select.vue'
import SelectTrigger from '@/components/ui/SelectTrigger.vue'
import SelectValue from '@/components/ui/SelectValue.vue'
import SelectContent from '@/components/ui/SelectContent.vue'
import SelectItem from '@/components/ui/SelectItem.vue'
import Tabs from '@/components/ui/Tabs.vue'
import TabsList from '@/components/ui/TabsList.vue'
import TabsTrigger from '@/components/ui/TabsTrigger.vue'
import TabsContent from '@/components/ui/TabsContent.vue'
import { BookOpen, Users, Star } from '@lucide/vue'

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)
const role = ref('student')
const email = ref('')
const password = ref('')
const name = ref('')
const regEmail = ref('')
const regPassword = ref('')
const errorMessage = ref('')

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  
  const success = await authStore.login({
    email: email.value,
    password: password.value
  })
  
  isLoading.value = false
  
  if (success) {
    router.push('/home')
  } else {
    errorMessage.value = 'Credenciales inválidas'
  }
}

const handleRegister = async () => {
  isLoading.value = true
  errorMessage.value = ''
  
  const success = await authStore.register({
    name: name.value,
    email: regEmail.value,
    password: regPassword.value,
    role: role.value
  })
  
  isLoading.value = false
  
  if (success) {
    router.push('/home')
  } else {
    errorMessage.value = 'Error al registrar la cuenta. El correo podría estar en uso.'
  }
}
</script>

<template>
  <div class="min-h-screen bg-background">
    <div class="flex min-h-screen">
      <!-- Left Side - Illustration -->
      <div class="hidden bg-accent lg:flex lg:w-1/2 lg:flex-col lg:items-center lg:justify-center lg:p-12">
        <div class="max-w-md text-center text-accent-foreground">
          <div class="mb-8 flex justify-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white p-2 shadow-lg border border-border">
              <img src="/logo.png" alt="TÚ ASESORÍA Logo" class="h-full w-full object-contain" />
            </div>
          </div>
          <h1 class="mb-4 text-4xl font-bold text-balance">Welcome to TÚ ASESORÍA</h1>
          <p class="mb-8 text-lg text-accent-foreground/80">
            Connect with university students and teachers for academic advising and tutoring sessions
          </p>
          
          <div class="grid grid-cols-3 gap-6">
            <div class="flex flex-col items-center gap-2">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/20">
                <BookOpen class="h-6 w-6 text-primary" />
              </div>
              <span class="text-sm font-medium">100+ Subjects</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/20">
                <Users class="h-6 w-6 text-primary" />
              </div>
              <span class="text-sm font-medium">5K+ Tutors</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/20">
                <Star class="h-6 w-6 text-primary" />
              </div>
              <span class="text-sm font-medium">4.9 Rating</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side - Auth Form -->
      <div class="flex w-full items-center justify-center p-6 lg:w-1/2">
        <Card class="w-full max-w-md border-0 shadow-xl">
          <CardHeader class="space-y-1 text-center">
            <div class="mb-4 flex justify-center lg:hidden">
              <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white p-1.5 border border-border shadow-sm">
                <img src="/logo.png" alt="TÚ ASESORÍA Logo" class="h-full w-full object-contain" />
              </div>
            </div>
            <CardTitle class="text-2xl font-bold">TÚ ASESORÍA</CardTitle>
            <CardDescription>Sign in to your account or create a new one</CardDescription>
          </CardHeader>
          <CardContent>
            <Tabs default-value="login" class="w-full">
              <TabsList class="grid w-full grid-cols-2">
                <TabsTrigger value="login">Login</TabsTrigger>
                <TabsTrigger value="register">Register</TabsTrigger>
              </TabsList>

              <TabsContent value="login">
                <form @submit.prevent="handleLogin" class="space-y-4">
                  <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input id="email" v-model="email" type="email" placeholder="student@university.edu" required />
                  </div>
                  <div class="space-y-2">
                    <Label for="password">Password</Label>
                    <Input id="password" v-model="password" type="password" placeholder="Enter your password" required />
                  </div>
                  <div v-if="errorMessage" class="text-sm text-red-500 font-medium text-center">
                    {{ errorMessage }}
                  </div>
                  <Button type="submit" class="w-full" :disabled="isLoading">
                    {{ isLoading ? "Signing in..." : "Sign In" }}
                  </Button>
                  
                  <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                      <span class="w-full border-t border-border" />
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                      <span class="bg-card px-2 text-muted-foreground">Or continue with</span>
                    </div>
                  </div>
                  
                  <Button variant="outline" type="button" class="w-full">
                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                      <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                      <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                      <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Continue with Google
                  </Button>
                </form>
              </TabsContent>

              <TabsContent value="register">
                <form @submit.prevent="handleRegister" class="space-y-4">
                  <div class="space-y-2">
                    <Label for="name">Full Name</Label>
                    <Input id="name" v-model="name" type="text" placeholder="Juan Pérez" required />
                  </div>
                  <div class="space-y-2">
                    <Label for="reg-email">Email</Label>
                    <Input id="reg-email" v-model="regEmail" type="email" placeholder="student@university.edu" required />
                  </div>
                  <div class="space-y-2">
                    <Label for="reg-password">Password</Label>
                    <Input id="reg-password" v-model="regPassword" type="password" placeholder="Create a password" required />
                  </div>
                  <div class="space-y-2">
                    <Label>Profile Type</Label>
                    <Select v-model="role" required>
                      <SelectTrigger>
                        <SelectValue placeholder="Select your role" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="student">Student</SelectItem>
                        <SelectItem value="tutor">Tutor</SelectItem>
                        <SelectItem value="teacher">Teacher</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <Button type="submit" class="w-full" :disabled="isLoading">
                    {{ isLoading ? "Creating account..." : "Create Account" }}
                  </Button>
                  
                  <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                      <span class="w-full border-t border-border" />
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                      <span class="bg-card px-2 text-muted-foreground">Or continue with</span>
                    </div>
                  </div>
                  
                  <Button variant="outline" type="button" class="w-full">
                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                      <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                      <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                      <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Continue with Google
                  </Button>
                </form>
              </TabsContent>
            </Tabs>
          </CardContent>
        </Card>
      </div>
    </div>
  </div>
</template>
