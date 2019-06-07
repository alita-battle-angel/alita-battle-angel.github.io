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
        })
      } else {
        body.style.backgroundPositionY = 0
      }
    }

    window.addEventListener('scroll', this.scrollHandler)
  },
  destroyed() {
    window.removeEventListener('scroll', this.scrollHandler)
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
      const expiry = new Date()
      expiry.setTime(new Date().getTime() + (10 * 24 * 60 * 60 * 1000))
      document.cookie = 'notify=true; expires=' + expiry.toGMTString()
      this.cookieNotify = document.cookie.indexOf('notify=true') !== -1
    }
  }
}
</script>

<style lang="scss">

</style>
