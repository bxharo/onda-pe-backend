import { defineStore } from 'pinia'

interface Loader {
  status: boolean
  route: string
  error?: boolean
}

interface LoaderMain {
  status: boolean
  cached: string[]
  error: boolean
}

interface General {
  data: any
  loading: boolean
}

interface AppStore {
  general: General
  loader: LoaderMain
  pageData: any
}

export const useAppStore = defineStore('app', {
  state: (): AppStore => ({
    general: {
      data: {
        information: {},
        primaryMenu: []
      },
      loading: true
    },
    loader: {
      status: true,
      cached: [],
      error: false
    },
    pageData: null
  }),
  getters: {
    loaderCached(): string[] {
      return this.loader.cached
    },
    loaderStatus(): boolean {
      return this.loader.status
    },
    loaderError(): boolean {
      return this.loader.error
    },
    generalPrimaryMenu(): any[] {
      return this.general.data.primaryMenu
    },
    api(): string {
      const hostname = window.location.hostname
      const protocol = window.location.protocol

      return import.meta.env.VITE_APP_API ?? `${protocol}//${hostname}/wp-json/custom/v1`
    }
  },
  actions: {
    // ACCIÓN QUE DETERMINA CUÁNDO MOSTRAR LA PANTALLA DE CARGA Y CUANDO QUITARLA
    updateLoader(payload: Loader): void {
      this.loader.status = payload.status

      if (payload.error) {
        this.loader.error = payload.error
      }

      if (!payload.status && !this.loader.cached.includes(payload.route)) {
        this.loader.cached.push(payload.route)
      }
    },
    // ACCIÓN PARA DATOS GLOBALES (Menús)
    async getGeneralData(): Promise<void> {
      const response = await fetch(`${this.api}/pages/general/?type=general`)

      if (response.status === 201 || response.status < 300) {
        const { data } = await response.json()

        this.general.data.information = data.information
        this.general.data.primaryMenu = data.primary_menu
        this.general.loading = false
      }
    },
    // ACCIÓN PARA TRAER PÁGINA O POST
    async getPageData(slug: string, type: string = 'page', typeName: string = ''): Promise<void> {
      try {
    const url = `${this.api}/pages/${slug}?type=${type}&type-name=${typeName}`
    console.log("🚀 Pidiendo datos a:", url) // Verifica si la URL es igual a la que funciona
    
    const response = await fetch(url)
    
    if (response.ok) {
      const json = await response.json()
      console.log("📦 JSON recibido desde PHP:", json)
      
      // IMPORTANTE: Quitamos el .data porque tu PHP devuelve el objeto directo
      this.pageData = json 
    } else {
      console.error("❌ Error en respuesta:", response.status)
    }
  } catch (error) {
    console.error("💥 Error crítico en fetch:", error)
    this.pageData = null
  }

      }
    }
  
})
