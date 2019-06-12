<template>
  <a
    class="hunter-warrior-info"
    :href="account_url"
    target="_blank"
  >
    <div class="content" />
    <img
      v-if="profile.profile_banner_url"
      class="banner"
      :src="profile.profile_banner_url"
      :alt="profile.name +' banner'"
    >
    <div class="user">
      <img :src="profile.profile_image_url_https" :alt="profile.name">
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
  computed: {
    account_url() {
      return 'https://twitter.com/' + this.profile.screen_name
    }
  },
  methods: {}
}
</script>
<style lang="scss">
  .hunter-warrior-info {
    width: 100%;
    margin: 4px;
    background-color: #2c3139;
    color: #bbb;
    border: none;
    font-size: 1em;
    border-radius: 0;
    padding: 0;
    transition: all .2s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;

    &:hover {
      background-color: #56606a;
      box-shadow: 0 5px 23px rgba(#555, .58), 0 0 2px 1px #222;
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
