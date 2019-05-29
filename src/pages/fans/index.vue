<template>
  <div class="page">
    <div class="page-row">
      <div class="box">
        <header>
          <h1 class="title">
            Fans
          </h1>
          <h2>
            Find out what fans are up to
          </h2>
          <p>
            This page is under construction and more contents & projects related to Alita will be added!
          </p>
        </header>
        <main class="articles">
          <h3 v-if="!tweets.length">Loading...</h3>
          <div class="column">
            <article
              class="tweet"
              v-for="tweet in eventTweets"
              v-bind:key="tweet.id"
            >
              <div class="twitter-user-info">
                <div class="action user">
                  <img :src="tweet.user.profile_image_url_https" :alt="tweet.user.name" />
                  <div>
                    <strong>{{tweet.user.name}}</strong>
                    <span>@{{tweet.user.screen_name}}</span>
                  </div>
                </div>
                <a class="action tweet" :href="getTweetLink(tweet)" target="_blank">
                  <i class="fab fa-twitter"></i>
                </a>
              </div>
              <p v-html="getParsedText(tweet)"></p>
              <p class="thin">{{tweet.created_at}}</p>
            </article>
          </div>
          <div class="column">
            <article
              class="tweet"
              v-for="tweet in oddTweets"
              v-bind:key="tweet.id"
            >
              <div class="twitter-user-info">
                <div class="action user">
                  <img :src="tweet.user.profile_image_url_https" :alt="tweet.user.name" />
                  <div>
                    <strong>{{tweet.user.name}}</strong>
                    <span>@{{tweet.user.screen_name}}</span>
                  </div>
                </div>
                <a class="action tweet" :href="getTweetLink(tweet)" target="_blank">
                  <i class="fab fa-twitter"></i>
                </a>
              </div>
              <p v-html="getParsedText(tweet)"></p>
            </article>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  head: {
    title: 'Fans | I Do Not Stand by in The Presence of Evil',
    meta: [
      {
        name: 'description',
        content: 'Explore all the projects and contents made by Alita\'s fans, also known as Alita Army.'
      }
    ],
    script: [
      {
        defer: true,
        src: 'https://platform.twitter.com/widgets.js',
        charset: 'utf-8'
      }
    ]
  },
  components: {},
  data: () => {
    return {
      tweets: []
    }
  },
  computed: {
    oddTweets() {
      return this.tweets.filter((item, i) => i % 2 === 0)
    },
    eventTweets() {
      return this.tweets.filter((item, i) => i % 2 !== 0)
    }
  },
  mounted() {
    this.fetchTweets()
  },
  methods: {
    async fetchTweets() {
      const tweetIds = [
        '1132276020843880448',
        '1130514726914596866',
        '1129912507576070150',
        '1126524230483496961',
        '1120750607806214149',
        '1117501789849444355'
      ]
      const response = await fetch('https://ewcms.org/alita-battle-angel/twitter.php?id=' + tweetIds.join(','))
      const tweets = await response.json()
      this.tweets = tweets || []
    },
    getParsedText(tweet) {
      const media = tweet.entities.media ? tweet.entities.media[0] : {}

      let withHashTags = tweet.full_text.replace(/#([^\s]*)/g, (hash, text) => {
        return `<a href="https://twitter.com/hashtag/${text}">${hash}</a>`
      })

      if (media.url) {
        withHashTags = withHashTags.replace(media.url, `<img src="${media.media_url_https}"/>`)
      }

      return withHashTags
    },
    getTweetLink(tweet) {
      return `https://twitter.com/${tweet.user.screen_name}/status/${tweet.id_str}`
    },
    getUserLink(tweet) {
      return `https://twitter.com/${tweet.user.screen_name}`
    }
  }
}
</script>

<style lang="scss">
  blockquote.twitter-tweet {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    font-family: "Helvetica Neue", Roboto, "Segoe UI", Calibri, sans-serif;
    font-size: 11px;
    font-weight: bold;
    line-height: 15px;
    margin: 0 0 10px;

    > a {
      align-self: flex-end;
      text-align: right;
      margin: 0;
      font-weight: inherit;
      font-size: 12px;
      padding: 4px 10px;
    }

    > .user {
      font-size: inherit;
      font-weight: inherit;
      margin-bottom: 5px;
    }

    > p {
      font-size: 14px;
      font-weight: normal;
      line-height: 20px;

      > a {
        padding: 1px 6px;
        line-height: 20px;
      }
    }
  }
</style>
