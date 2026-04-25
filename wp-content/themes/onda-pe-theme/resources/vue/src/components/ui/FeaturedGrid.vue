<script setup lang="ts">
import ArticlePreviewImage from './ArticlePreviewImage.vue'

interface Post {
  id: number
  category: string
  title: string
  image: string
  url: string
}

defineProps<{
  posts: Post[]
}>()
</script>

<template>
  <section class="c-featured-grid container">
    <div v-for="post in posts" :key="post.id" class="c-featured-item">
      <router-link :to="post.url" class="c-featured-link">
        <div class="c-featured-image">
          <ArticlePreviewImage :source="post.image" />
        </div>
        <div class="c-featured-content">
          <span class="c-tag-celeste">{{ post.category }}</span>
          <h3 class="c-featured-title">{{ post.title }}</h3>
        </div>
      </router-link>
    </div>
  </section>
</template>