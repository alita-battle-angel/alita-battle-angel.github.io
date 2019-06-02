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
          <h3 v-if="!tweets.length">
            Loading...
          </h3>
          <div class="column">
            <article-tweet
              v-for="tweet in oddTweets"
              :key="tweet.id"
              :tweet="tweet"
            >
              <article-tweet v-if="tweet.in_reply_to_status" class="reply" :tweet="tweet.in_reply_to_status" />
            </article-tweet>
          </div>
          <div class="column">
            <article-tweet
              v-for="tweet in evenTweets"
              :key="tweet.id"
              :tweet="tweet"
            >
              <article-tweet v-if="tweet.in_reply_to_status" class="reply" :tweet="tweet.in_reply_to_status" />
            </article-tweet>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script>
import ArticleTweet from '~/components/article-tweet'

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
  components: {
    ArticleTweet
  },
  data: () => {
    return {
      tweets: []
    }
  },
  computed: {
    oddTweets() {
      return this.tweets.filter((item, i) => i % 2 === 0)
    },
    evenTweets() {
      return this.tweets.filter((item, i) => i % 2 === 1)
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
        '1117501789849444355',
        '1112391562628136960',
        '1135116404901011457',
        '1131952934579625984',
        '1134923821516038145'
      ]
      const response = await fetch('https://ewcms.org/alita-battle-angel/twitter.php?id=' + tweetIds.join(','))
      const tweets = await response.json()
      this.tweets = tweets || []
    },
    getParsedText(tweet) {
      if (!tweet) {
        return ''
      }

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
    },
    getTweetDate(tweet) {
      if (!tweet) {
        return ''
      }

      return new Date(tweet.created_at).toLocaleString()
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
