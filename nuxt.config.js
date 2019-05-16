module.exports = {
  mode: 'universal',

  srcDir: 'src/',
  /*
  ** Headers of the page
  */
  head: {
    title: 'I Do Not Stand by in The Presence of Evil',
    meta: [
      { charset: 'utf-8' },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      },
      {
        hid: 'description',
        name: 'description',
        content: 'For Alita\'s fan'
      },
      {
        name: 'og:image',
        content: '/social-banner.jpg'
      },
      {
        name: 'twitter:card',
        content: 'summary_large_image'
      },
      {
        name: 'twitter:url',
        content: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/'
      },
      {
        name: 'twitter:title',
        content: 'I Do Not Stand by in the Presence of Evil'
      },
      {
        name: 'twitter:description',
        content: 'A community hub for Alita\'s fandom.'
      },
      {
        name: 'twitter:image',
        content: '/social-banner.jpg'
      }
    ],
    link: [
      {
        rel: 'icon',
        type: 'image/png',
        href: '/favicon.png'
      }
    ],
    script: []
  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },

  /*
  ** Global CSS
  */
  css: [
    '~/assets/main.scss'
  ],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [],

  /*
  ** Nuxt.js modules
  */
  modules: [
    '@nuxtjs/pwa'
  ],

  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
