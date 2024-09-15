// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  ssr: true,
  devtools: {
    enabled: true,

    timeline: {
      enabled: true
    }
  },

  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.BASE_URL
    }
  },
  app: {
    head: {
      title: 'Hotel Management',
      htmlAttrs: {
        lang: 'en',
        dir: 'ltr'
      },
      meta: [
        {charset: 'utf-8'},
        {name: 'viewport', content: 'width=device-width, initial-scale=1'},
        {hid: 'description', name: 'description', content: ''}
      ],
    },
  },

  css: [
    "assets/css/bootstrap.min.css",
    "assets/css/material-icons.min.css",
    "assets/scss/_variable.scss",
    "assets/css/fontawesome.min.css",
    "sweetalert2/dist/sweetalert2.min.css"
  ],

  plugins: ['~/plugins/bootstrap.client.ts','~/plugins/windowSize.client.ts'],
})