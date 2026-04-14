<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useAppStore } from '@/stores/app'
import LoaderApp from '@/components/LoaderApp.vue'
import HeaderMain from '@/components/header/HeaderMain.vue'
import FooterMain from '@/components/FooterMain.vue'

const store = useAppStore()

onMounted(async () => {
  try {
    await store.getGeneralData()
  } catch (error) {
    console.error("Error cargando datos de WP:", error)
  } finally {
    // Usamos la función oficial de tu Store que descubrimos antes
    store.updateLoader({ 
        status: false,
        route: '' 
    })
  }
})
</script>

<template>
  <section class="c-app">
    <HeaderMain />
    
    <section class="c-app__section" :class="{ loaded: !store.loaderStatus }">
      <RouterView v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <KeepAlive>
            <component :is="Component" />
          </KeepAlive>
        </transition>
      </RouterView>
    </section>

    <FooterMain />
    <LoaderApp :status="store.loaderStatus" />
  </section>
</template>
