<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const article = ref<any>(null)
const fields = ref<any>(null)
const loading = ref(true)

// 1. Obtenemos el slug de la URL (/single/este-es-el-slug)
const slug = route.params.slug

onMounted(async () => {
  try {
    // 2. Pedimos la data a tu API personalizada de WP
    // Ajusta la URL según tu configuración de carpetas
    const url = `http://localhost/wp-json/custom/v1/pages/${slug}?type=post-type&type-name=post`
    const response = await axios.get(url);
    
    if (response.data.status) {
      // Guardamos el post y los campos adicionales (ACF)
      article.value = response.data.data.post
      fields.value = response.data.data.fields;
    }
  } catch (error) {
    console.error("Error cargando el artículo:", error)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div v-if="loading" class="c-loading">Cargando artículo...</div>

  <main v-else-if="article" class="p-article">
    <div class="container-article">
      
      <article class="c-article">
        <header class="c-article__header">
          <span class="c-article__tag">{{ fields?.categoria || 'General' }}</span>
          <h1 class="c-article__title">{{ article.post_title }}</h1>
          <div class="c-article__meta">
            Por <span class="c-article__author">{{ article.author_name || 'Autor' }}</span> — 
            <span class="c-article__date">{{ new Date(article.post_date).toLocaleDateString() }}</span>
          </div>
        </header>
          
        <div class="c-article__body" v-html="article.post_content"></div>

      </article>

    </div>
  </main>

  <div v-else class="c-error">
    No se pudo encontrar el artículo con el slug: {{ slug }}
  </div>
</template>

<style lang="scss">
/* Asegúrate de que el loader y el error tengan algo de estilo básico */
.c-loading, .c-error {
  text-align: center;
  padding: 100px 20px;
  color: white;
}
</style>