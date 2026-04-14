<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import HomeHero from '@/components/ui/HomeHero.vue'
import SidebarMain from '@/components/ui/SidebarMain.vue'
import HomeCategorySection from '@/components/ui/HomeCategorySection.vue';
import BaseView from '@/views/core/BaseView.vue';
import { useAppStore } from '@/stores/app'

const store = useAppStore()

//Recibimos el 'content' que viene desde App.vue
defineProps<{
  content: any
}>()

onMounted(async () => {
  // 1. Pedimos la data del Hero (Soberanía Digital)
  // Activará el caso 'home' en  __getPostData del Controller
  await store.getPageData('home', 'page', 'page')
  
  // 2. Ahora que la Home tiene sus datos, quitamos el loader
  store.updateLoader({ status: false, route: '/' })
  console.log("Revisando el post:", store.pageData?.post)
})

const heroData = computed(() => {

  // Accedemos a los datos inyectados en el Controller(HERO)
  const post = store.pageData?.data?.hero;
  
  if (!post) {
    return { 
      tag: '', 
      title: '', 
      description: '', 
      image: '' 
    };
  }
  
  return {
    tag: (post.category_name || 'ACTUALIDAD').toUpperCase(),
    title: post.title_home || 'Cargando...',
    description: post.post_excerpt || '',
    image: post.hero_image || ''
  }
 
})

const featuredPosts = computed(() => {
  // Accedemos a los datos inyectados en el Controller (DESTACADAS)
  const posts = store.pageData?.data?.destacadas || [];

  // Mapeamos para que coincida con la estructura de tu componente
  return posts.map((post: any, index: number) => ({
    id: post.ID,
    category: `0${index + 1} / ${(post.category_name || 'DESTACADO').toUpperCase()}`,
    title: post.title_home,
    description: post.post_excerpt,
    url: post.link // Timber provee el link directo
  })).slice(0, 3); // Solo tomamos las primeras 3 para la Triple Section

})

const gridTriplePosts = ref([
  { id: 1, title: 'Jazz en el centro.', excerpt: 'La movida nocturna...', image: 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80' },
  { id: 2, title: 'Noches de Barranco.', excerpt: 'Un recorrido por...', image: 'https://images.unsplash.com/photo-1514328537553-6058093e9866?q=80' },
  { id: 3, title: 'Campus Híbridos.', excerpt: 'Las universidades...', image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80' }
])

const gridDoublePosts = ref([
  { id: 1, title: 'El renacer del Centro.', excerpt: 'Desde casonas...', image: 'https://images.unsplash.com/photo-1493612276216-ee3925520721?q=80' },
  { id: 2, title: 'Relojería andina.', excerpt: 'Una nueva generación...', image: 'https://images.unsplash.com/photo-1509048191080-d2984bad6ad5?q=80' }
])

const economyPosts = [
  { id: 1, meta: 'Banca', title: 'BCR mantiene tasas bajas.', description: 'El Banco Central de Reserva ha decidido mantener su política de tasas mínimas para estimular el consumo interno y facilitar el acceso a créditos hipotecarios en el sector inmobiliario nacional este trimestre.', image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80' },
  { id: 2, meta: 'Agro', title: 'Récord de Arándanos en el norte.', description: 'Gracias a la optimización de los sistemas de riego y nuevos acuerdos comerciales, las exportaciones de arándanos hacia los mercados asiáticos han registrado un crecimiento histórico del 40% durante el primer semestre.' },
  { id: 3, meta: 'Inversión', title: 'Fintech levanta $20M en ronda A.', description: 'Una destacada plataforma de banca abierta local ha cerrado una importante ronda de capitalización liderada por inversores europeos, consolidando el atractivo del ecosistema tecnológico financiero peruano ante el mundo.' },
  { id: 4, meta: 'Puerto', title: 'Chanchay: Fase 2 inicia obras.', description: 'Una destacada plataforma de banca abierta local ha cerrado una importante ronda de capitalización liderada por inversores europeos, consolidando el atractivo del ecosistema tecnológico financiero peruano ante el mundo.' },
 
  // ... más posts
];

const techPosts = [
  { id: 1, meta: 'IA', title: 'BCR mantiene tasas bajas.', description: 'El Banco Central de Reserva ha decidido mantener su política de tasas mínimas para estimular el consumo interno y facilitar el acceso a créditos hipotecarios en el sector inmobiliario nacional este trimestre.', image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80' },
  { id: 2, meta: 'Hardware', title: 'Récord de Arándanos en el norte.', description: 'Gracias a la optimización de los sistemas...' },
  { id: 3, meta: 'Ciber', title: 'Fintech levanta $20M en ronda A.', description: 'Una destacada plataforma de banca abierta local ha cerrado una importante ronda de capitalización liderada por inversores europeos, consolidando el atractivo del ecosistema tecnológico financiero peruano ante el mundo.' },
  { id: 4, meta: 'Espacio', title: 'Fintech levanta $20M en ronda A.', description: 'Una destacada plataforma de banca abierta local ha cerrado una importante ronda de capitalización liderada por inversores europeos, consolidando el atractivo del ecosistema tecnológico financiero peruano ante el mundo.' },
 
  // ... más posts
];

</script>

<template>
 <BaseView :content="content">
      <div class="p-home c-main-layout"> 
        
        <div class="c-content-area">
          <HomeHero 
            v-bind="heroData"
            class="is-hero"
          />

          <section class="c-triple-section">
            <div v-for="post in featuredPosts" :key="post.id" class="c-article-preview is-triple">
              <span class="c-article-preview-tag">{{ post.category }}</span>
              <h3 class="c-article-preview-title">{{ post.title }}</h3>
              <p class="c-article-preview-excerpt">{{ post.description }}</p>
            </div>
          </section>

          <section class="c-grid-row-3">
            <article v-for="post in gridTriplePosts" :key="post.id" class="c-article-preview is-grid">
              <div class="c-article-preview-image-wrapper">
                <img :src="post.image" :alt="post.title">
              </div>
              <h4 class="c-article-preview-title">{{ post.title }}</h4>
              <p class="c-article-preview-excerpt">{{ post.excerpt }}</p>
            </article>
          </section>

          <section class="c-row-text-only">
            <span class="c-article-preview-tag">Especial // Sostenibilidad</span>
            <h2 class="c-article-preview-title">Riego Ancestral...</h2>
            <p class="c-article-preview-excerpt">Frente a las sequías...</p>
          </section>

          <section class="c-grid-row-2">
            <article v-for="post in gridDoublePosts" :key="post.id" class="c-article-preview is-grid">
              <div class="c-article-preview-image-wrapper">
                <img :src="post.image" :alt="post.title">
              </div>
              <h4 class="c-article-preview-title">{{ post.title }}</h4>
              <p class="c-article-preview-excerpt">{{ post.excerpt }}</p>
            </article>
          </section>
        </div>

        <aside class="c-sidebar"> 
          <SidebarMain />
        </aside>

      </div> 

      <div class="c-category-wrapper">
        <HomeCategorySection title="Economía" :posts="economyPosts" />
        <HomeCategorySection title="Tecnología" :posts="techPosts" />
      </div>

  </BaseView>
</template>

<style lang="scss" scoped>
@use '@/assets/styles/base/variables';
@use '@/assets/styles/components/hero';
@use '@/assets/styles/base/layout'; 
@use '@/assets/styles/pages/home';


</style>