<template>
  <article
    class="article-tweet"
  >
    <slot />
    <div class="twitter-user-info">
      <div class="action user">
        <img :src="tweet.profile_image_url_https" :alt="tweet.name">
        <div>
          <strong>{{ tweet.name }}</strong>
          <span>@{{ tweet.screen_name }}</span>
        </div>
      </div>
      <a class="action btn-see-tweet" :href="tweet_url" target="_blank">
        <i class="fab fa-twitter" />
      </a>
    </div>
    <p class="text" v-html="parsed_text" />
    <p class="thin">
      {{ date }}
    </p>
  </article>
</template>
<script>
export default {
  props: {
    tweet: {
      type: Object,
      default: null
    }
  },
  computed: {
    parsed_text () {
      return this.getParsedText(this.tweet)
    },
    tweet_url () {
      return this.getTweetURL(this.tweet)
    },
    user_url () {
      return `https://twitter.com/${this.tweet.screen_name}`
    },
    date () {
      return this.getTweetDate(this.tweet)
    }
  },
  methods: {
    getParsedText (tweet) {
      if (!tweet) {
        return ''
      }

      const media = tweet.media ? tweet.media[0] : {}

      const withMentions = tweet.full_text.replace(/@([^\s]*)/g, (at, text) => {
        return `<a class="link-white" href="https://twitter.com/${text}">${at}</a>`
      })

      let withHashTags = withMentions.replace(/#([^\s]*)/g, (hash, text) => {
        return `<a class="inline" href="https://twitter.com/hashtag/${text}">${hash}</a>`
      })

      if (media.url) {
        withHashTags = withHashTags.replace(media.url, `<img src="${media.media_url_https}"/>`)
      }

      tweet.urls.split('\n').forEach((item) => {
        withHashTags = withHashTags.replace(item, `<a href="${item}" target="_blank">${item}</a>`)
      })

      return withHashTags
    },
    getTweetURL (tweet) {
      return `https://twitter.com/${tweet.screen_name}/status/${tweet.id_str}`
    },
    getTweetDate (tweet) {
      if (!tweet) {
        return ''
      }

      return new Date(tweet.status_created_at).toLocaleString()
    }
  }
}
</script>
<style lang="scss">
  .article-tweet {
    &.reply {
      > p {
        padding-left: 48px;
        border-left: 2px solid #aaa;
        margin: 15px 0 15px 32px;

        &:last-child {
          margin-top: -15px;
          margin-bottom: 15px;
        }
      }
    }

    > p {
      margin: 15px 0;

      &:last-child {
        margin-bottom: 0;
      }

      &.text {
        white-space: pre-wrap;
      }

      > a {
        padding: 0 4px 0 3px;
        margin: 0 -1px;

        &.link-white {
          padding: 0 5px 1px 3px;
        }
      }

      > img {
        margin-top: 15px;
        width: 100%;
      }
    }

    > .thin {
      color: #7e6f90;
    }

    .link-white {
      /*font-weight: 400;*/
    }
  }

  .twitter-user-info {
    display: flex;

    > .user {
      height: 64px;
      flex: 1 1 auto;
      overflow: hidden;
      background-color: #fff1;
      margin-right: 4px;
      padding: 8px;

      &:first-child {
        display: flex;
        align-items: center;
        font-size: .88em;
        padding-right: 10px;

        > div {
          overflow: hidden;
        }

        strong, span {
          display: block;
          white-space: nowrap;
          text-overflow: ellipsis;
          overflow: hidden;
        }
      }

      > img {
        border-radius: 50%;
        margin-right: 10px;
      }
    }

    > .btn-see-tweet {
      width: 64px;
      height: 64px;
      text-align: center;
      font-size: 32px;
      line-height: 48px;
      background-color: #fff1;
      padding: 8px;
    }

  }
</style>
