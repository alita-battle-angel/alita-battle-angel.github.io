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
        content: 'width=device-width, initial-scale=1, user-scalable=no'
      },
      {
        hid: 'description',
        name: 'description',
        content: 'For Alita\'s fan'
      },
      {
        name: 'twitter:card',
        content: 'summary_large_image'
      },
      {
        hid: 'twitter:url',
        name: 'twitter:url',
        content: 'https://i-do-not-stand-by-in-the-presence-of-evil.com'
      },
      {
        hid: 'twitter:title',
        name: 'twitter:title',
        content: 'I Do Not Stand by in the Presence of Evil'
      },
      {
        hid: 'twitter:description',
        name: 'twitter:description',
        content: 'A community hub for Alita\'s fandom.'
      },
      {
        hid: 'twitter:image',
        name: 'twitter:image',
        content: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/social-banner.jpg'
      },
      {
        hid: 'og:title',
        name: 'og:title',
        content: 'I Do Not Stand by in The Presence of Evil'
      },
      {
        hid: 'og:site_name',
        name: 'og:site_name',
        content: 'I Do Not Stand by in The Presence of Evil'
      },
      {
        hid: 'og:url',
        name: 'og:url',
        content: 'https://i-do-not-stand-by-in-the-presence-of-evil.com'
      },
      {
        hid: 'og:description',
        name: 'og:description',
        content: 'For Alita\'s fan'
      },
      {
        name: 'og:type',
        content: 'website'
      },
      {
        hid: 'og:image',
        name: 'og:image',
        content: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/social-banner.jpg'
      }
    ],
    link: [
      {
        hid: 'favicon',
        rel: 'icon',
        type: 'image/png',
        href: '/favicon.png'
      }
    ],
    script: [
      {
        defer: true,
        src: 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/plugins/CSSPlugin.min.js'
      },
      {
        defer: true,
        src: 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/easing/EasePack.min.js'
      },
      {
        defer: true,
        src: 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenLite.min.js'
      },
      {
        defer: true,
        src: 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/plugins/ScrollToPlugin.min.js'
      }
    ]
  },

  /*
  ** Customize the progress-bar color
  */
  loading: {
    color: '#aaa',
    continuous: true,
    duration: 1000
  },

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
  modules: [],

  router: {
    scrollBehavior: function (to, from, savedPosition) {
      return false
    }
  },

  workbox: {
    dev: true
  },

  manifest: {
    icons: [
      {
        'src': '/favicon.png',
        'sizes': '144x144',
        'type': 'image/png'
      },
      {
        'src': '/favicon.png',
        'sizes': '192x192',
        'type': 'image/png'
      },
      {
        'src': '/favicon.png',
        'sizes': '512x512',
        'type': 'image/png'
      }
    ]
  },
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
          exclude: [/(node_modules)/, /(_nuxt)/]
        })
      }
    }
  }
}
