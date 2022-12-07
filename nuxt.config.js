export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  srcDir: 'src/',
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
        content: 'A community hub for Alita: Battle Angle\'s fandom. Learn more about what fans are up to.'
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
        content: 'A community hub for Alita: Battle Angle\'s fandom. Learn more about what fans are up to.'
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
        content: 'A community hub for Alita: Battle Angle\'s fandom. Learn more about what fans are up to.'
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

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/main.scss'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
  ],

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },
  loadingIndicator: {
    name: 'circle',
    color: '#aaa',
    background: 'white'
  },
}
