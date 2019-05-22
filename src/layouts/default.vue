<template>
  <div>
    <div class="header-box">
      <nav>
        <nuxt-link
          v-for="item in menus"
          :key="item.href"
          :to="item.href"
          exact-active-class="active"
        >
          <i class="material-icons">{{ item.icon }}</i>
          <span>
            {{ item.title }}
          </span>
        </nuxt-link>
      </nav>
    </div>

    <nuxt />
  </div>
</template>

<script>
// import { TweenLite } from 'gsap'

let height = 1

export default {
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
      ]
    }
  },
  // watch: {
  //   $route(to, f) {
  //     const newHeight = document.querySelector('#__nuxt').getBoundingClientRect().height
  //     console.log('---', to, height, newHeight)
  //   }
  // },
  mounted() {
    const body = document.querySelector('body')
    const nav = document.querySelector('.header-box > nav')
    console.log(this.$nuxt)

    this.scrollHandler = (event) => {
      requestAnimationFrame(() => {
        const max = (window.innerHeight - nav.getBoundingClientRect().height) / 2
        const scrollY = window.scrollY
        const space = document.scrollingElement.scrollHeight - window.innerHeight
        height = document.querySelector('#__nuxt').getBoundingClientRect().height
        // console.log(height)
        if (scrollY) {
          body.style.backgroundPositionY = -Math.floor((scrollY * (max * 0.55)) / space) + 'px'
          nav.style.transform = 'translateY(' + -Math.floor((scrollY * (max * 0.95)) / space) + 'px)'
        } else {
          body.style.backgroundPositionY = 0
          nav.style.transform = 'translateY(0)'
        }
      })
    }

    window.addEventListener('scroll', this.scrollHandler)
  },
  destroyed() {
    window.removeEventListener('scroll', this.scrollHandler)
  }
}
</script>

<style lang="scss">

</style>
