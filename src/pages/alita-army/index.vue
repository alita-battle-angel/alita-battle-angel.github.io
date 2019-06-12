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
            You can join the Alita Army by registering your twitter username
          </p>
          <h3>
            Why join?
          </h3>
          <p>
            Because "Alita Army" is a passionate fanbase of Alita's fans that positively support her. We will give our
            heart to her because she would do the same.
          </p>
          <p>
            Also, because we are not bots.
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
        </header>
        <div class="pagination">
          <button :disabled="can_previous" @click="previous()">
            <i class="material-icons">keyboard_arrow_left</i>
          </button>
          <span>
            {{ page }} / {{ total_pages }}
          </span>
          <button :disabled="can_next" @click="next()">
            <i class="material-icons">keyboard_arrow_right</i>
          </button>
        </div>

        <main :class="list_state">
          <HunterWarrior v-for="hunter in data" :key="hunter.id_str" :profile="hunter" />
        </main>
      </div>
    </div>
  </div>
</template>

<script>
import HunterWarrior from '~/components/hunter-warrior'

const unAllowedChars = [' ', '@', '#', '-', '=', '+', '(', ')', '*', '^', '%', '&', '!', '~']
export default {
  head: {
    title: 'Alita Army | I Do Not Stand by in The Presence of Evil',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content: 'List of members of the Alita Army. You can join the Alita Army too!'
      }
    ]
  },
  components: {
    HunterWarrior
  },
  data: () => {
    return {
      data: [],
      screen_name: '',
      message: null,
      fetching: false,
      registering: false,
      total_pages: 1,
      page: 1
    }
  },
  computed: {
    list_state() {
      return { hunters: true, loading: this.fetching }
    },
    can_previous() {
      return this.page <= 1
    },
    can_next() {
      return this.total_pages <= this.page
    }
  },
  watch: {
    '$route.query.page': 'fetchArmy'
  },
  mounted() {
    this.page = this.$route.query.page || 1
    this.fetchArmy()
  },
  methods: {
    async fetchArmy(page) {
      this.fetching = true
      const response = await fetch('https://ewcms.org/alita-battle-angel/alita-army.php?page=' + (page || this.page))
      const jsonResponse = await response.json()
      this.data = jsonResponse.data || []
      this.total_pages = jsonResponse.total_pages
      this.page = jsonResponse.page
      this.fetching = false
    },
    enroll() {
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
          this.fetchArmy()
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
            message: error.message
          })
        }).then(() => {})
        this.showMessage('Something went wrong, please try again later.')
      })

      this.showMessage('Registering... please wait')

      return false
    },
    filterChar(event) {
      if (unAllowedChars.indexOf(event.key) !== -1) {
        event.preventDefault()
      }
    },
    showMessage(text) {
      this.$nextTick(() => {
        this.message = text

        clearTimeout(this._messageTimeout)
        this._messageTimeout = setTimeout(() => {
          this.message = null
        }, 7000)
      })
    },
    next() {
      if (this.total_pages > this.page) {
        this.$router.push({ path: this.$route.path, query: { page: this.page + 1 } })
      }
    },
    previous() {
      if (this.page > 1) {
        this.$router.push({ path: this.$route.path, query: { page: this.page - 1 } })
      }
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
      max-width: calc(33.33% - 8px);
    }

    @media only screen and (max-width: 1000px) {
      > .hunter-warrior-info {
        max-width: calc(50% - 8px);
      }
    }

    @media only screen and (max-width: 600px) {
      > .hunter-warrior-info {
        max-width: calc(100% - 8px);
      }
    }
  }
</style>
