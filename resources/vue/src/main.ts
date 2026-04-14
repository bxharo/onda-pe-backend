import { createApp, defineAsyncComponent } from 'vue'
import { createPinia } from 'pinia'
import { Icon } from '@iconify/vue'

import App from './App.vue'
import router from './router' //Gestiona las urls

import './assets/styles/main.scss'

import PrimeVue from 'primevue/config'  //Tu librería de componentes de interfaz (botones, menús, etc.)
import Aura from '@primeuix/themes/aura'
import Drawer from 'primevue/drawer'
import Dialog from 'primevue/dialog'
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';

const pinia = createPinia() // Base de datos interna del navegador
const app = createApp(App) //Usar el App.vue como componente padre de todos los demás.

//Componentes globales
app.component('IconUI', Icon)
app.component('DrawerUI', Drawer)
app.component('DialogUI', Dialog)
app.component('ToastUI', Toast)

app.component(
  'FooterMain',
  defineAsyncComponent(() => import('@/components/FooterMain.vue'))//Componente asíncrono
)

app.component(
  'LoaderApp',
  defineAsyncComponent(() => import('@/components/LoaderApp.vue'))//Componente asíncrono
)

app.component(
  'FormMain',
  defineAsyncComponent(() => import('@/components/FormMain.vue'))//Componente asíncrono
)

app.component(
  'SidebarMain',
  defineAsyncComponent(() => import('@/components/ui/SidebarMain.vue'))
)

app.component(
  'ArticlePreview',
  defineAsyncComponent(() => import('@/components/ui/ArticlePreview.vue'))
)

app.use(router)
app.use(pinia)
app.use(PrimeVue, {
  inputVariant: 'filled',
  theme: {
    preset: Aura,
    options: {
      prefix: 'p',
      darkModeSelector: 'light',
      cssLayer: false
    }
  }
})
app.use(ToastService)

app.mount('#app')
