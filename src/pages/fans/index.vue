<template>
  <div class="page">
    <div class="page-row">
      <div class="box">
        <header>
          <h1 class="title">
            Fans
          </h1>
          <h2>
            Find out about the latest stuff
          </h2>
          <p>
            Would you like to add a specific tweet!? then contact <a
              href="https://twitter.com/EeliyaKing"
              target="_blank"
            >@EeliyaKing</a>
          </p>
        </header>

        <pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />

        <div class="filters">
          <nuxt-link
            v-for="item in filterItems"
            :key="item"
            :class="{toggle: true, active: isFilterActive(item)}"
            :to="getHref(item)"
          >
            #{{ item }}
          </nuxt-link>
        </div>

        <main :class="list_state">
          <div class="column">
            <article-tweet
              v-for="tweet in oddTweets"
              :key="tweet.id"
              :tweet="tweet"
            >
              <article-tweet v-if="tweet.in_reply_to_status" :tweet="tweet.in_reply_to_status" class="reply"/>
            </article-tweet>
          </div>
          <div class="column">
            <article-tweet
              v-for="tweet in evenTweets"
              :key="tweet.id"
              :tweet="tweet"
            >
              <article-tweet v-if="tweet.in_reply_to_status" :tweet="tweet.in_reply_to_status" class="reply"/>
            </article-tweet>
          </div>
        </main>

        <pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from '~/components/pagination'
import ArticleTweet from '~/components/article-tweet'

const fetchPageData = async (page, filterBy) => {
  const response = await fetch('https://ewcms.org/alita-battle-angel/fans.php?page=' + (page || '') + '&filterBy=' + (filterBy || ''))
  return response.json()
}

export default {
  head () {
    const filterBy = this.$store.state.tweets.filterBy ? ' #' + this.$store.state.tweets.filterBy : ''
    const filterByText = filterBy ? 'Alita Army, filtered by' + filterBy : 'Alita\'s fans, also known as Alita Army'
    const filterByQuery = this.$store.state.tweets.filterBy ? '?filterBy=' + this.$store.state.tweets.filterBy : ''

    const pageNumber = parseInt(this.$route.query.page || 0)
    const canonical = pageNumber === this.$store.state.tweets.total_pages ? {
      rel: 'canonical',
      href: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/fans' + filterByQuery
    } : {}

    const pageNumberText = pageNumber ? `| Page ${pageNumber} ` : ''

    const users = this.$store.state.tweets.data.map((tweet) => {
      return '@' + tweet.screen_name
    })

    const uniqueUsers = []

    users.forEach((user) => {
      if (!uniqueUsers.includes(user)) {
        uniqueUsers.push(user)
      }
    })

    const usersText = 'Posts by ' + uniqueUsers.join(' ')

    return {
      title: `Fans${filterBy} ${pageNumberText}| I Do Not Stand by in The Presence of Evil`,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: `Fan made content. Click to find out more about the latest stuff made by ${filterByText}.\n${usersText}`
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
  data () {
    return {

      filterItems: [
        'AlitaArmy',
        'AlitaSequel',
        'FanArt',
        'Cosplay',
        'AlitaChallenge',
        'Alita',
        'AlitaBattleAngel',
        'AlitaDay'
      ],
      lastQuery: null
    }
  },
  computed: {
    activeFilter () {
      return this.$store.state.tweets.filterBy
    },
    fetching () {
      return this.$store.state.tweets.fetching || false
    },
    data () {
      return this.$store.state.tweets.data || []
    },
    oddTweets () {
      return this.data.filter((item, i) => i % 2 === 0)
    },
    evenTweets () {
      return this.data.filter((item, i) => i % 2 === 1)
    },
    total_pages () {
      return this.$store.state.tweets.total_pages || 1
    },
    page () {
      return this.$store.state.tweets.page || 1
    },
    list_state () {
      return { articles: true, loading: this.fetching }
    },
    previous_url () {
      return '/fans?page=' + (this.page - 1) + (this.activeFilter ? '&filterBy=' + this.activeFilter : '')
    },
    next_url () {
      return '/fans?page=' + (this.page + 1) + (this.activeFilter ? '&filterBy=' + this.activeFilter : '')
    }
  },
  async validate ({ store, query }) {
    store.commit('tweets/set', { fetching: true, filterBy: query.filterBy })
    const response = await fetchPageData(query.page, query.filterBy)
    store.commit('tweets/set', response)
    store.commit('tweets/set', { fetching: false, filterBy: query.filterBy })

    const page = parseInt(query.page || 1)
    return page <= store.state.tweets.total_pages && page > 0
  },
  methods: {
    isFilterActive (item) {
      return this.activeFilter === item
    },
    getHref (item) {
      if (this.activeFilter === item) {
        return { query: {} }
      }

      return { query: { filterBy: item } }
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

  .filters {
    padding: 15px 0;
    margin-bottom: 15px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

    > a {
      margin: 5px;
    }
  }
</style>
