<template>
  <div>
    <div class="header-bar">
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
        }
      ]
    }
  }
}
</script>

<style lang="scss">
  .header-bar {
    position: fixed;
    width: calc(100% - 20px);
    padding: 0 10px;
    left: 0;
    top: 10px;
    z-index: 10;

    > nav {
      background-color: #0f1318;
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      flex-direction: row;
      align-items: center;
      overflow: hidden;
      position: relative;
      padding: 10px 5px;
      border-radius: 10px;
      box-shadow: 0 0 6px rgba(#000, .15), 0 10px 23px rgba(#000, .25);

      &::before {
        content: ' ';
        position: absolute;
        background-image: url('~assets/textures/brushed-alum.png');
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: .28;
        z-index: 0;
      }

      > a {
        width: 86px;
        line-height: 18px;
        padding: 10px 5px;
        border: 1px solid #333;
        border-radius: 5px;
        color: #ccc;
        background-color: rgba(#000, .3);
        font-weight: 300;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0 5px;
        text-align: center;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        z-index: 1;

        > i {
          font-size: 40px;
          font-weight: 300;
        }

        > span {
          margin-top: 10px;
        }

        &:hover {
          background-color: rgba(#009ae5, .15);
          border-color: rgba(#009ae5, .16);
        }

        &.active {
          color: #fff;
          background-color: rgba(#009ae5, .3);
          border: 1px solid #009ae5;
          text-shadow: 0 0 3px #fff;
          box-shadow: 0 0 9px 1px rgba(#009ae5, .6);
        }
      }
    }
  }

  body {
    padding-top: 60px;
  }
</style>
