<template>
  <div>
    <div class="header-box">
      <nav :class="{'expand': expand}">
        <nuxt-link
          v-for="item in menus"
          :key="item.href"
          :to="item.href"
          exact-active-class="active"
          @click.native="shrink()"
        >
          <i class="material-icons">{{ item.icon }}</i>
          <span>
            {{ item.title }}
          </span>
        </nuxt-link>

        <button class="mob-icon menu-btn" @click="toggleExpand($event)" @touchstart="toggleExpand($event)">
          <i class="material-icons">menu</i>
        </button>
      </nav>
    </div>

    <nuxt />

    <footer>
      <p>
        Released under the
        <a class="link-white" href="https://opensource.org/licenses/MIT" target="_blank">MIT
          License</a>
      </p>
      <p>
        Source code available at
        <a
          class="link-white"
          href="https://github.com/alita-battle-angel/alita-battle-angel.github.io"
          target="_blank"
        >
          GitHub
        </a>
      </p>
    </footer>
    <div v-if="!cookieNotify" class="cookie-popup">
      <p>
        Our website use cookies. By continuing, you agree to their use
      </p>
      <button @click="acceptCookies()">
        Got it
      </button>
    </div>
  </div>
</template>

<script>

let height = 1
let lastScrollY = 0

export default {
  data() {
    return {
      cookieNotify: true,
      menus: [
        {
          title: 'Home',
          href: '/',
          icon: 'power_settings_new'
        },
        {
          title: 'Statistics',
          href: '/statistics',
          icon: 'show_chart'
        },
        {
          title: 'Fans',
          href: '/fans',
          icon: 'group'
        },
        {
          title: 'Alita Army',
          href: '/alita-army',
          icon: 'stars'
        },
        {
          title: 'Merchandise',
          href: '/merchandise',
          icon: 'shopping_cart'
        }
      ],
      expand: false
    }
  },
  mounted() {
    this.cookieNotify = document.cookie.indexOf('notify=true') !== -1
    const body = document.querySelector('body')

    this.scrollHandler = () => {
      const max = (document.scrollingElement.scrollHeight - window.innerHeight)
      lastScrollY = window.scrollY

      if (lastScrollY) {
        requestAnimationFrame(() => {
          body.style.backgroundPositionY = -Math.floor((lastScrollY * Math.floor(max * 0.15)) / max) + 'px'

          // const layer5 = document.querySelectorAll('.layer-5')
          // const maxLayer5 = max * 0.25
          // Array.prototype.forEach.call(layer5, (node) => {
          //   node.style.transform = 'translateY(' + ((maxLayer5 / 2) - Math.floor((lastScrollY * maxLayer5) / space)) + 'px)'
          // })
        })
      } else {
        body.style.backgroundPositionY = 0
      }
    }

    const __nuxt = document.querySelector('#__nuxt')
    const maxScrollDistance = 500
    const maxScrollTime = 0.5
    this.hightHandlerInterval = setInterval(() => {
      const currentHeight = Math.floor(document.querySelector('#__layout').getBoundingClientRect().height || 0)
      // console.log(document.scrollingElement.scrollHeight - window.innerHeight, window.scrollY)
      if (height !== currentHeight) {
        height = currentHeight
        const time = Math.min(maxScrollTime, (window.scrollY * maxScrollTime) / maxScrollDistance)

        window.TweenLite.to(window, time || 0.01, {
          scrollTo: { y: 0 },
          ease: 'Strong.easeInOut',
          onComplete() {
            __nuxt.style.height = Math.max(height, window.innerHeight) + 'px'
          }
        })
      }
    }, 100)

    window.addEventListener('scroll', this.scrollHandler)

    requestAnimationFrame(() => {
      this.scrollHandler()
    })
  },
  destroyed() {
    window.removeEventListener('scroll', this.scrollHandler)
    clearInterval(this.hightHandlerInterval)
  },
  methods: {
    toggleExpand(event) {
      if (event.type === 'click' && window.hasOwnProperty('ontouchstart')) {
        return
      }

      this.expand = !this.expand
    },
    shrink() {
      this.expand = false
    },
    updatePerspective() {

    },
    acceptCookies() {
      document.cookie = 'notify=true'
      this.cookieNotify = document.cookie.indexOf('notify=true') !== -1
    }
  }
}
</script>

<style lang="scss">

</style>
