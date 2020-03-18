<template>
  <a
    :href="account_url"
    @click="reload($event)"
    class="hunter-warrior-info"
    target="_blank"
  >
    <div class="content" />
    <img
      v-if="profile.profile_banner_url"
      :src="profile.profile_banner_url"
      :alt="profile.name +' banner'"
      @error="onImageError()"
      class="banner"
    >
    <div class="user">
      <img :src="profile.profile_image_url_https" :alt="profile.name" @error="onImageError()">
      <div>
        <strong>{{ profile.name }}</strong>
        <span>@{{ profile.screen_name }}</span>
      </div>
    </div>
    <p>{{ profile.location }}</p>
  </a>
</template>
<script>
export default {
  props: {
    profile: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      reloadable: false
    }
  },
  computed: {
    account_url () {
      return 'https://twitter.com/' + this.profile.screen_name
    }
  },
  methods: {
    onImageError () {
      this.reloadable = true
    },
    reload (event) {
      if (this.reloadable) {
        event.preventDefault()
        this.reloadable = false
        fetch('https://ewcms.org/alita-battle-angel/alita-army.php', {
          method: 'post',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            screen_name: this.profile.screen_name,
            register: true
          })
        }).then((response) => {
          return response.json()
        }).then((response) => {
          if (response.data) {
            this.$store.commit('alita-army/update', response.data)
          } else {
            this.$store.commit('alita-army/remove', this.profile)
          }
        })
      }
    }
  }
}
</script>
<style lang="scss">
  .hunter-warrior-info {
    width: 100%;
    margin: 4px;
    background-color: #2b476444;
    border: 1px solid #fff1;
    color: #bbb;
    /*border: none;*/
    font-size: 1em;
    border-radius: 0;
    padding: 0;
    transition: all .2s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;

    &:hover {
      background-color: #2b4764;
      box-shadow: 0 6px 23px #2b4764aa, 0 0 2px 1px #2b4764aa;
      border-color: #fff2;
      color: #fff;
    }

    > * {
      position: relative;
    }

    > .content {
      display: block;
      padding-top: 33.33%;
    }

    > .banner {
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
    }

    > .user {
      display: flex;
      overflow: hidden;
      bottom: 0;
      left: 0;
      right: 0;
      font-size: 12px;
      align-items: center;
      padding: 8px 8px 0;

      > div {
        overflow: hidden;
      }

      strong, span {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        margin: 0;
        line-height: 16px;
        display: block;
      }

      strong {
        font-size: 14px;
        margin-bottom: 4px;
      }

      span {
        font-weight: 300;
      }

      > img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-right: 8px;
        flex: 0 0 auto;
      }
    }

    > p {
      display: block;
      margin: 0;
      font-size: 10px;
      text-align: right;
      line-height: 12px;
      padding: 8px;
    }
  }
</style>
