<script setup lang="ts">
import { RouterLink } from 'vue-router'
const year = new Date().getFullYear()
</script>

<template>
  <footer class="c-footer">
    <div class="c-footer-content container">
      
      <div class="c-footer-brand">
        <div class="footer-logo">
          <RouterLink to="/">
            Onda<span>.</span>
          </RouterLink>
        </div>
        <p class="footer-tagline">
          Narrando la evolución cultural y tecnológica del Perú desde el corazón de la capital. Reportaje de profundidad para el 2026.
        </p>
      </div>

      <div class="c-footer-nav">
        <h5>Secciones</h5>
        <nav>
          <RouterLink to="/reportajes">Reportajes</RouterLink>
          <RouterLink to="/especiales">Especiales</RouterLink>
          <RouterLink to="/opinion">Opinión</RouterLink>
          <RouterLink to="/archivo">Archivo 1976</RouterLink>
        </nav>
      </div>

      <div class="c-footer-subscribe">
        <h5>Suscríbete al Brief</h5>
        <p>Lo más relevante de la semana analizado por nuestros editores.</p>
        <form class="c-subscribe-form" @submit.prevent>
          <input type="email" placeholder="tu@email.com" required>
          <button type="submit">Unirse</button>
        </form>
      </div>
    </div>

    <div class="c-footer-bottom container">
      <div class="social-links">
        <a href="#">Instagram</a>
        <a href="#">X / Twitter</a>
        <a href="#">Linkedin</a>
        <a href="#">Newsletter</a>
      </div>
      <div class="copyright">
        © {{ year }} ONDA MEDIOS S.A. — LIMA, PERÚ
      </div>
    </div>
  </footer>
</template>

