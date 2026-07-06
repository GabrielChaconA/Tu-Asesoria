<script setup>
const currentYear = new Date().getFullYear()
</script>

<template>
  <footer class="bg-accent border-t border-border mt-auto py-6 text-accent-foreground">
    <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-sm">
      <div class="mb-4 md:mb-0 text-accent-foreground/70">
        &copy; {{ currentYear }} TÚ ASESORÍA. Todos los derechos reservados.
      </div>
      <div class="flex space-x-6">
        <a href="#" class="text-accent-foreground/70 hover:text-accent-foreground transition-colors">Política de Privacidad</a>
        <a href="#" class="text-accent-foreground/70 hover:text-accent-foreground transition-colors">Términos de Servicio</a>
        <a href="#" class="text-accent-foreground/70 hover:text-accent-foreground transition-colors">Contacto</a>
      </div>
    </div>
  </footer>
</template>
