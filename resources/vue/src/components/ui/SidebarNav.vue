<script setup lang="ts">
// Recibe 'modelValue' para el v-model desde el Header
defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits(['update:modelValue'])

// Función para cerrar el sidebar
const closeSidebar = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <DrawerUI 
    class="c-sidebar"
    :model-value="modelValue" 
    position="right"
    @update:model-value="emit('update:modelValue', $event)"
  >
    <div class="c-sidebar-inner">
      <div class="c-sidebar-header">
        <span class="c-sidebar-logo">Onda<span>.</span></span>
      </div>
      
      <nav class="c-sidebar-nav">
        <ul class="ul-reset">
          <li><router-link to="/" @click="closeSidebar">Inicio</router-link></li>
          <li><router-link to="/politica" @click="closeSidebar">Política</router-link></li>
          <li><router-link to="/arte" @click="closeSidebar">Arte</router-link></li>
          <li><router-link to="/tecnologia" @click="closeSidebar">Tecnología</router-link></li>
          <li><router-link to="/negocios" @click="closeSidebar">Negocios</router-link></li>
        </ul>
      </nav>

      <div class="c-sidebar-footer">
        <p>EDICIÓN 02 / 2026</p>
      </div>
    </div>
  </DrawerUI>
</template>