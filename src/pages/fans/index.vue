<template>
  <div class="page">
    <div class="page-row">
      <div class="box">
        <header>
          <h1 class="title">
            Fans
          </h1>
          <h2>
            Find out about latest stuff
          </h2>
          <p>
            Would you like to add a specific tweet!? then contact <a
              href="https://twitter.com/EeliyaKing"
              target="_blank"
            >@EeliyaKing</a>
          </p>
        </header>

        <Pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />

        <main :class="list_state">
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

        <Pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from '~/components/pagination'
import ArticleTweet from '~/components/article-tweet'

const fetchPageData = async (page) => {
  const response = await fetch('https://ewcms.org/alita-battle-angel/fans.php?page=' + (page || 1))
  return response.json()
}

export default {
  head() {
    const canonical = this.$store.state.tweets.page <= 1 ? {
      rel: 'canonical',
      href: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/fans'
    } : {}

    return {
      title: 'Fans | I Do Not Stand by in The Presence of Evil',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'Explore all the contents made by Alita\'s fans, also known as Alita Army.'
        }
      ],
      link: [
        canonical
      ]
    }
  },
  components: {
    Pagination,
    ArticleTweet
  },
  data() {
    return {
      fetching: false
    }
  },
  computed: {
    data() {
      return this.$store.state.tweets.data || []
    },
    oddTweets() {
      return this.data.filter((item, i) => i % 2 === 0)
    },
    evenTweets() {
      return this.data.filter((item, i) => i % 2 === 1)
    },
    total_pages() {
      return this.$store.state.tweets.total_pages || 1
    },
    page() {
      return this.$store.state.tweets.page || 1
    },
    list_state() {
      return { articles: true, loading: this.fetching }
    },
    previous_url() {
      return '/fans?page=' + (this.page - 1)
    },
    next_url() {
      return '/fans?page=' + (this.page + 1)
    }
  },
  watch: {
    '$route.query.page': 'fetchTweets'
  },
  async fetch({ query, store }) {
    const response = await fetchPageData(query.page)
    store.commit('tweets/set', response)
  },
  methods: {
    async fetchTweets(page) {
      this.fetching = true
      const response = await fetch('https://ewcms.org/alita-battle-angel/fans.php?page=' + (page || this.page))
      const jsonResponse = await response.json()
      this.$store.commit('tweets/set', jsonResponse)
      this.fetching = false
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
