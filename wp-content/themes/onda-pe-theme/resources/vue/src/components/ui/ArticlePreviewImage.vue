<script setup lang="ts">
// @ts-ignore
import VLazyImage from 'v-lazy-image'

defineProps<{
  source: string
  ratio?: string
}>()
</script>

<template>
  <div 
    class="c-article-preview-image"
    :style="{ aspectRatio: ratio || '16 / 9' }"
  >
    <v-lazy-image 
      :src="source" 
      class="v-lazy-image" 
      alt="Onda. editorial content"
    />
  </div> 
</template>

<style lang="scss" scoped>
@use '@/assets/styles/base/variables';

.c-article-preview-image {
  position: relative;
  width: 100%;
  overflow: hidden;
  background-color: variables.$gray-light; // Usamos tu variable oficial

  .v-lazy-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover; 
    object-position: center;
    opacity: 0;
    transition: opacity 0.4s ease-in-out;

    &.v-lazy-image-loaded {
      opacity: 1;
    }
  }
}
</style>