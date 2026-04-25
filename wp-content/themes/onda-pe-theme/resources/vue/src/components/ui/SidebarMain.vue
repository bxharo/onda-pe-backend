<script setup lang="ts">
// Aquí podrías importar sub-componentes más adelante para limpiar el código
</script>

<template>
  <aside class="c-sidebar-editorial">
    <section class="c-section">
      <h3 class="c-section__title">Lo más leído</h3>
      <div class="c-ranking">
        <div v-for="n in 3" :key="n" class="c-ranking__item">
          <span class="c-ranking__number">{{ n }}</span>
          <div class="c-ranking__text">
            <h4>El fin de las oficinas físicas en el sector tech local.</h4>
            <p>Empresas de San Isidro devuelven locales ante el auge del trabajo remoto 100%.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="c-section">
    <h3 class="c-section__title">Opinión</h3>
    <div class="c-opinion">
        <div class="c-opinion__item">
          <img src="https://i.pravatar.cc/150?u=1" alt="Autor" class="c-opinion__avatar" />
          <div class="c-opinion__content">
            <span class="c-opinion__author">Augusto Álvarez</span>
            <h4 class="c-opinion__title">La fragilidad de los consensos en el Congreso.</h4>
          </div>
        </div>

        <div class="c-opinion__item">
          <img src="https://i.pravatar.cc/150?u=2" alt="Autor" class="c-opinion__avatar" />
          <div class="c-opinion__content">
            <span class="c-opinion__author">María Rostworowski</span>
            <h4 class="c-opinion__title">Releyendo los mitos que nos fundaron como nación.</h4>
          </div>
        </div>
    </div>
    </section>

    <section class="c-section">
    <h3 class="c-section__title">Editor's Pick</h3>
    <div class="c-editors-pick">
      <div class="c-pick-item">
        <div class="c-pick-item__image">
          <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?q=80&w=300" alt="Pick" />
        </div>
        <div class="c-pick-item__content">
          <span class="c-tag-mini">REPORTAJE</span>
          <h4>El último artesano de sombreros en el Rímac.</h4>
        </div>
      </div>
    
      <div class="c-pick-item">
        <div class="c-pick-item__image">
          <img src="https://images.unsplash.com/photo-1523995421752-03f33946d42e?q=80&w=300" alt="Pick" />
        </div>
        <div class="c-pick-item__content">
          <span class="c-tag-mini">ENTREVISTA</span>
          <h4>Conversación con la nueva ola del cine andino.</h4>
        </div>
      </div>
    </div>
    </section>

    <section class="c-section">
    <h3 class="c-section__title">ARCHIVO: 1976</h3>
    
    <div class="c-archive-card">
      <span class="c-archive-card__today">TAL DÍA COMO HOY</span>
      <h2 class="c-archive-card__headline">
        "Se inaugura el primer tramo de la Vía Expresa."
      </h2>
      <p class="c-archive-card__excerpt">
        Las crónicas de la época destacaban la modernización del transporte en la capital.
      </p>
      <a href="#" class="c-archive-card__link">VER EDICIÓN COMPLETA</a>
      
      <span class="c-archive-card__bg-number">76</span>
    </div>
    </section>

    <section class="c-section">
    <div class="c-market-bar">
      <div class="c-market-bar__row">
        <span class="c-market-bar__location">LIMA</span>
        <span class="c-market-bar__weather">24°C Soleado</span>
      </div>
      
      <div class="c-market-bar__data">
        <div class="c-market-item">
          USD/PEN 3.72 <span class="c-market-item--down">▼</span>
        </div>
        <div class="c-market-item">
          IBVL +0.45% <span class="c-market-item--up">▲</span>
        </div>
        <div class="c-market-item">
          BTC $92k <span class="c-market-item--down">▼</span>
        </div>
      </div>
    </div>
  </section>

  </aside>
</template>