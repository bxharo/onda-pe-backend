<script setup lang="ts">
import ArticlePreviewImage from './ArticlePreviewImage.vue'

defineProps<{
  title: string
  tag: string
  description: string
  image: string
  url: string;
}>()
</script>

<template>
  <section class="c-hero">
    <a :href="url" class="c-hero__link">
      <div class="c-hero-content">
        <div class="c-hero-text">
          <span class="c-hero-tag">{{ tag }}</span>
          <h1 class="c-hero-title">{{ title }}</h1>
          <p class="c-hero-description">{{ description }}</p>
        </div>

        <div class="c-hero-image-wrapper">
          <ArticlePreviewImage :source="image" class="c-hero-img-component"/>
        </div>
      </div>
    </a>
  </section>
</template>

<style lang="scss" scoped>
/* Déjalo vacío. Todo el estilo ahora vive en _hero.scss */
</style>