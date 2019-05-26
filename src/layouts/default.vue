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

        <a class=" mob-icon" @click="toggleExpand($event)">
          <i class="material-icons">menu</i>
        </a>
      </nav>
    </div>

    <nuxt />

    <footer>
      <p>Released under the <a href="https://opensource.org/licenses/MIT" target="_blank">MIT License</a></p>
      <p>
        Source code available at
        <a
          href="https://github.com/alita-battle-angel/alita-battle-angel.github.io"
          target="_blank"
        >
          GitHub
        </a>
      </p>
    </footer>
  </div>
</template>

<script>

let height = 1
let lastScrollY = 0

export default {
  head: {
    script: [
      {
        defer: true,
        src: 'https://platform.twitter.com/widgets.js',
        charset: 'utf-8'
      }
    ]
  },
  data() {
    return {
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
          title: 'Merchandise',
          href: '/merchandise',
          icon: 'shopping_cart'
        }
      ],
      expand: false
    }
  },
  mounted() {
    const body = document.querySelector('body')
    const nav = document.querySelector('.header-box > nav')

    this.scrollHandler = () => {
      requestAnimationFrame(() => {
        const max = (window.innerHeight - nav.getBoundingClientRect().height) / 2
        lastScrollY = window.scrollY
        const space = document.scrollingElement.scrollHeight - window.innerHeight

        if (lastScrollY) {
          body.style.backgroundPositionY = -Math.floor((lastScrollY * (max * 0.35)) / space) + 'px'
          nav.style.transform = 'translateY(' + -Math.floor((lastScrollY * (max * 0.95)) / space) + 'px)'
        } else {
          body.style.backgroundPositionY = 0
          nav.style.transform = 'translateY(0)'
        }
      })
    }

    const __nuxt = document.querySelector('#__nuxt')
    this.hightHandlerInterval = setInterval(() => {
      const currentHeight = document.querySelector('#__layout').getBoundingClientRect().height
      if (height !== currentHeight) {
        height = currentHeight
        window.TweenLite.to(window, 0.5, { scrollTo: { y: 0 }, ease: 'Strong.easeInOut' })
        window.TweenLite.to(__nuxt, 0.6, {
          height: Math.max(height, window.innerHeight - 60),
          ease: 'Strong.easeInOut'
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
    toggleExpand() {
      this.expand = !this.expand
    },
    shrink() {
      this.expand = false
    }
  }
}
</script>

<style lang="scss">

</style>
