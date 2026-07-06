<script setup>
import { ref, computed } from 'vue'
import Button from '@/components/ui/Button.vue'
import { X, Upload } from '@lucide/vue'
import api from '@/services/api'
import { useAuthStore } from '@/stores/authStore'

const props = defineProps({
  isOpen: Boolean,
  user: Object
})

const emit = defineEmits(['close', 'updated'])
const authStore = useAuthStore()

const formData = ref({
  name: props.user?.name || '',
  lastname: props.user?.lastname || '',
  bio: props.user?.bio || '',
})

const imageFile = ref(null)
const imagePreview = ref(props.user?.profileImageUrl || null)
const isSubmitting = ref(false)

const handleImageChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleSubmit = async () => {
  isSubmitting.value = true
  try {
    const data = new FormData()
    data.append('name', formData.value.name)
    data.append('lastname', formData.value.lastname)
    data.append('bio', formData.value.bio)
    if (imageFile.value) {
      data.append('profileImage', imageFile.value)
    }

    const response = await api.put('/profile', data, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    // Actualizar store local
    authStore.setAuth(response.data.user, authStore.token)
    
    emit('updated', response.data.user)
    emit('close')
  } catch (error) {
    console.error('Error updating profile:', error)
    alert('Error al actualizar el perfil')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-background/80 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-lg border border-border bg-card p-6 shadow-lg">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Editar Perfil</h2>
        <button @click="$emit('close')" class="text-muted-foreground hover:text-foreground">
          <X class="h-5 w-5" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        
        <!-- Foto -->
        <div class="flex flex-col items-center gap-4 mb-6">
          <div class="relative h-24 w-24 overflow-hidden rounded-full border border-border bg-muted flex items-center justify-center">
            <img v-if="imagePreview" :src="imagePreview" class="h-full w-full object-cover" />
            <span v-else class="text-sm text-muted-foreground">Sin Foto</span>
          </div>
          <div>
            <label class="cursor-pointer text-sm text-primary hover:underline flex items-center gap-2">
              <Upload class="h-4 w-4" />
              Cambiar foto
              <input type="file" class="hidden" accept="image/*" @change="handleImageChange" />
            </label>
          </div>
        </div>

        <!-- Nombre y Apellido -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1">
            <label class="text-sm font-medium">Nombre</label>
            <input v-model="formData.name" type="text" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm" required />
          </div>
          <div class="space-y-1">
            <label class="text-sm font-medium">Apellido</label>
            <input v-model="formData.lastname" type="text" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm" required />
          </div>
        </div>

        <!-- Biografía -->
        <div class="space-y-1">
          <label class="text-sm font-medium">Biografía</label>
          <textarea v-model="formData.bio" rows="4" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm"></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4">
          <Button type="button" variant="outline" @click="$emit('close')" :disabled="isSubmitting">Cancelar</Button>
          <Button type="submit" :disabled="isSubmitting">
            {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
