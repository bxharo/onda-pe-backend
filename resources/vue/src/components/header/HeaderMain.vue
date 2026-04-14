<script setup lang="ts">
import { useIsAtiveMenuItem } from '@/composable/header'
import { ref } from 'vue'
import SidebarNav from '@/components/ui/SidebarNav.vue' // Importamos la nueva pieza

const visible = ref<boolean>(false)
const isAtiveMenuItem = useIsAtiveMenuItem
</script>

<template>
  <header class="c-header">
    <div class="l-container"> 
      
      <div class="c-header-top">
        <router-link to="/" class="c-logo">
          Onda<span>.</span>
        </router-link>
        
        <div class="c-ed-info">
          EDICIÓN 02 / 2026<br>
          LIMA, PERÚ
        </div>
      </div>

      <nav class="c-nav">
        <div class="c-nav-inner">
          <ul class="c-nav-list ul-reset">
            <li v-for="item in ['Política', 'Arte', 'Tecnología', 'Negocios', 'Cultura']" :key="item">
              <router-link 
                to="#" 
                class="c-nav-btn"
                :class="{ 'is-active': isAtiveMenuItem(item.toLowerCase()) }"
              >
                {{ item }}
              </router-link>
            </li>
          </ul>

        </div>
      </nav>

    </div> <SidebarNav v-model="visible" />
  </header>
</template>