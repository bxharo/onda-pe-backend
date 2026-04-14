<script setup lang="ts">
import ArticlePreviewImage from './ArticlePreviewImage.vue'

defineProps<{
  image: string
  title: string
  category?: string
  excerpt?: string
  author?: string
  date?: string
  link: string
  variant?: 'hero' | 'grid' | 'triple'
}>()
</script>

<template>
  <router-link 
      :to="link" 
      class="c-article-preview" 
      :class="variant ? `is-${variant}` : ''"
    >
      <div class="c-article-preview-image-wrapper">
        <ArticlePreviewImage :source="image" />
      </div>

      <div class="c-article-preview-content">
        <span v-if="category" class="c-article-preview-tag">{{ category }}</span>
        <h3 class="c-article-preview-title">{{ title }}</h3>
        <p v-if="excerpt" class="c-article-preview-excerpt">{{ excerpt }}</p>

        <div v-if="author || date" class="c-article-preview-meta">
          <span>{{ author }}</span>
          <span v-if="date"> · {{ date }}</span>
        </div>
      </div>
    </router-link>
</template>

<style lang="scss" scoped>
/* Nota: No ponemos estilos aquí porque ya los 
  centralizamos en _article-preview.scss 
*/
</style>