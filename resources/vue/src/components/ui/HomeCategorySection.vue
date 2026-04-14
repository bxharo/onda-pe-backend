<script setup lang="ts">
interface Post {
  id: number;
  meta: string;
  title: string;
  description: string;
  image?: string;
}

defineProps<{
  title: string;
  posts: Post[];
}>();
</script>

<template>
  <section class="c-category-section">
    <h2 class="c-category-title">{{ title }}</h2>
    <div class="c-category-grid">
      <a href="#" class="c-cat-item is-featured">
        <img :src="posts[0].image" alt="Featured">
        <div class="c-cat-text">
          <span class="c-meta">{{ posts[0].meta }}</span>
          <h4>{{ posts[0].title }}</h4>
          <p>{{ posts[0].description }}</p>
        </div>
      </a>

      <div class="c-cat-list">
        <a v-for="post in posts.slice(1)" :key="post.id" href="#" class="c-cat-item">
          <span class="c-meta">{{ post.meta }}</span>
          <h4>{{ post.title }}</h4>
          <p>{{ post.description }}</p>
        </a>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
@use '@/assets/styles/base/variables';

.c-category-section {
  margin-bottom: 60px;
}

.c-category-title {
  font-size: 14px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 25px;
  padding-bottom: 10px;
  border-bottom: 1px solid #000;
}

.c-category-grid {
  display: grid;
  gap: 45px;
  
  @media (min-width <= 1024px) {
    grid-template-columns: 1.6fr 1fr;
  }
}

.c-cat-item {
  text-decoration: none;
  color: inherit;
  display: block;
  border-right: 1px solid variables.$border-light;
  padding-right: 45px;
 
  &.is-featured {
    display: grid;
    gap: 25px;
    
    @media (min-width <= 768px) {
      grid-template-columns: 1.1fr 1fr;
    }
    

    img {
      width: 100%;
      height: 320px;
      object-fit: cover;
    }

    h4 {
      font-family: variables.$font-serif;
      font-size: 32px;
      line-height: 1.05;
      margin-bottom: 12px;
    }
  }

  &:not(.is-featured) {
    border-bottom: 1px solid variables.$border-light;
    padding-bottom: 18px;
    margin-bottom: 8px;
    &:last-child { border-bottom: none; }
  }

  .c-meta {
    font-size: 10px;
    font-weight: 900;
    color: variables.$accent-blue;
    text-transform: uppercase;
    margin-bottom: 8px;
    display: block;
  }

  h4 {
    font-size: 17px;
    font-weight: 800;
    line-height: 1.3;
    margin-bottom: 6px;
  }

  p {
    font-size: 13px;
    color: #555;
    line-height: 1.5;
  }
}
</style>