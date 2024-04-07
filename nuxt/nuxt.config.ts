// https://nuxt.com/docs/api/configuration/nuxt-
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'

export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: ['@nuxtjs/apollo',
    (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({ autoImport: true }))
      })
    },
    'nuxt-snackbar',
  ],
  
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Hydration tracker',
    }

  },
  snackbar: {
    bottom: true,
    right: true,
    duration: 5000
  },

  vite: {
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },

  apollo: {
    autoImports: true,
    clients: {
      default: {
        httpLinkOptions: {
          credentials: 'same-origin'
        },
        httpEndpoint: 'http://localhost/graphql',
        tokenName: 'apollo:web.token',
        authType: 'Bearer',
        authHeader: 'Authorization'
      }
    },
  },
  build: {
    transpile: [/vuetify/]
  },
})
