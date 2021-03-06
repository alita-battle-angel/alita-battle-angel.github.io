<template>
  <div class="page">
    <div class="page-row">
      <div class="box">
        <header>
          <h1 class="title">
            Alita Army
          </h1>
          <h2>
            Join the Alita Army
          </h2>
          <p>
            You can join the Alita Army by registering your twitter username.
          </p>
          <h3>
            Why join?
          </h3>
          <p>
            Because "Alita Army" is a passionate fanbase of Alita's fans that positively support her. We will give our
            heart to her because she would do the same. Check out <a href="https://alitaarmy.com">AlitaArmy.com</a> to learn about upcoming missions!
          </p>
          <p>
            Also check out the <a
              class="link-white"
              href="https://alitastan.fandom.com/wiki/The_Alita_Army"
            >
              The Alita Army
            </a> from Alitastan Wiki.
          </p>
          <p>
            And also, we are NOT bots.
          </p>
          <ul>
            <li>You need to have a <strong>#AlitaArmy</strong> in your twitter bio</li>
            <li>You need to have at least one post</li>
          </ul>
          <form class="inline" method="post" @submit.prevent="enroll()">
            <label class="field inline">
              <span>
                Twitter Username
              </span>
              <span class="icon">@</span>
              <input v-model="screen_name" @keydown="filterChar($event)">
            </label>
            <button type="submit" :disabled="registering">
              Register
            </button>
          </form>
          <p v-if="message" class="message">
            {{ message }}
          </p>
          <p v-if="error" class="message">
            If the problem persist please contact
            <a
              href="https://twitter.com/EeliyaKing"
              target="_blank"
            >@EeliyaKing</a>
          </p>
          <p style="text-align: center; color: #bbb; font-size: .9em;">
            This list is NOT an official list and only meant for enthusiast fans to show their support
          </p>
        </header>
        <pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />

        <main :class="list_state">
          <hunter-warrior v-for="hunter in data" :key="hunter.screen_name" :profile="hunter" />
        </main>

        <pagination :page="page" :total-pages="total_pages" :previous-url="previous_url" :next-url="next_url" />
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from '~/components/pagination'
import HunterWarrior from '~/components/hunter-warrior'

const fetchPageData = async (page) => {
  const response = await fetch('https://ewcms.org/alita-battle-angel/alita-army.php?page=' + (page || '') + '&item_per_page=30')
  return response.json()
}

const unAllowedChars = [' ', '@', '#', '-', '=', '+', '(', ')', '*', '^', '%', '&', '!', '~']
export default {
  head () {
    const pageNumber = parseInt(this.$route.query.page || 0)
    const canonical = pageNumber === this.$store.state['alita-army'].total_pages ? {
      rel: 'canonical',
      href: 'https://i-do-not-stand-by-in-the-presence-of-evil.com/alita-army'
    } : {}

    return {
      title: 'Alita Army | I Do Not Stand by in The Presence of Evil',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'You can join the Alita Army too! Just add a #AlitaArmy to your twitter bio and register. Click here to see the list of who already joined.'
        }
      ],
      link: [
        canonical
      ],
      bodyAttrs: {
        class: ['alita-army']
      }
    }
  },
  components: {
    Pagination,
    HunterWarrior
  },
  data: () => {
    return {
      screen_name: '',
      message: null,
      error: false,
      registering: false
    }
  },
  computed: {
    fetching () {
      return this.$store.state['alita-army'].fetching || false
    },
    data () {
      return this.$store.state['alita-army'].data || []
    },
    total_pages () {
      return this.$store.state['alita-army'].total_pages || 1
    },
    page () {
      return this.$store.state['alita-army'].page || 1
    },
    list_state () {
      return { hunters: true, loading: this.fetching }
    },
    previous_url () {
      return '/alita-army?page=' + (this.page - 1)
    },
    next_url () {
      return '/alita-army?page=' + (this.page + 1)
    }
  },
  async validate ({ store, query }) {
    store.commit('alita-army/set', { fetching: true })
    const response = await fetchPageData(query.page)
    store.commit('alita-army/set', response)
    store.commit('alita-army/set', { fetching: false })

    const page = parseInt(query.page || 1)
    return page <= store.state['alita-army'].total_pages && page > 0
  },
  methods: {
    enroll () {
      this.screen_name = this.screen_name.replace(' ', '')
      if (!this.screen_name) {
        return false
      }

      fetch('https://ewcms.org/alita-battle-angel/alita-army.php', {
        method: 'post',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          screen_name: this.screen_name,
          register: true
        })
      }).then((response) => {
        this.registering = false
        if (response.status === 200) {
          this.screen_name = ''
          this.$router.push({ path: this.$route.path })
        }

        if (response.status === 202) {
          this.$router.replace({ path: this.$route.path })
        }

        return response.json()
      }).then((data) => {
        this.showMessage(data.message)
      }).catch((error) => {
        fetch('https://ewcms.org/alita-battle-angel/send-error.php', {
          method: 'post',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            message: '<' + this.screen_name + '> ' + error.message + '\nuser agent: ' + window.navigator.userAgent
          })
        }).then(() => {
        })
        this.showMessage('Something went wrong, please try again later.', true)
      })

      this.showMessage('Registering... please wait')

      return false
    },
    filterChar (event) {
      if (unAllowedChars.includes(event.key)) {
        event.preventDefault()
      }
    },
    showMessage (text, error) {
      this.$nextTick(() => {
        this.message = text
        this.error = error

        clearTimeout(this._messageTimeout)
        this._messageTimeout = setTimeout(() => {
          this.message = null
          this.error = false
        }, error ? 15000 : 7000)
      })
    }
  }
}
</script>

<style lang="scss">
  .hunters {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;

    > .hunter-warrior-info {
      flex: 1 0 auto;
      max-width: calc(50% - 7px);
    }

    @media only screen and (max-width: 1000px) {
      > .hunter-warrior-info {
        font-size: .9em;
      }
    }

    @media only screen and (max-width: 600px) {
      > .hunter-warrior-info {
        max-width: 100%;
        margin: 10px 0;
        font-size: .9em;
      }
    }
  }
</style>
