<template>
  <div class="page">
    <article class="page-row">
      <div class="box f-col banner">
        <h1>I do not stand by in the presence of Evil</h1>
        <p>Join the Alita Army by
          <nuxt-link class="link-white" to="/alita-army">registering as a member</nuxt-link>
        </p>
        <div class="f-row">
          <a
            class="link-icon x-large "
            href="https://www.change.org/p/robert-rodriguez-alita-battle-angel-part-2"
            target="_blank"
          >
            <img
              height="188"
              src="~/assets/img/petition-banner.jpg"
              alt="petition logo"
            >
            <span>Alita's Sequel Petition</span>
            <strong>{{ petition.signatureCount.total }} have signed</strong>
          </a>

          <a
            class="link-icon x-large"
            href="https://alitaunbreakablewarrior.github.io/the-game.html"
            target="_blank"
          >
            <img
              height="188"
              src="~/assets/img/alita-unbreakable-warrior.jpg"
              alt="petition logo"
            >
            <span>Alita Unbreakable Warrior</span>
            <strong>RPG action game</strong>
          </a>

          <a
            class="link-icon x-large "
            href="https://discordapp.com/invite/DBkt7Qm"
            target="_blank"
          >
            <img
              height="188"
              src="~/assets/img/discord-thumbnail.jpg"
              alt="Discord"
            >
            <span>Gunnm/Alita: Battle Angel</span>
            <strong>Alita Army on Discord</strong>
          </a>
        </div>
      </div>
    </article>

    <article class="page-row">
      <div class="box split f-col">
        <h3 class="">
          In the mean while you can check following links
        </h3>
        <div class="f-col">
          <a href="https://shop.foxmovies.com/collections/alita">
            Official Alita's Merchandise
          </a>
          <a href="https://www.reddit.com/r/alitabattleangel/">
            Alita Battle Angel on reddit
          </a>
          <a href="https://www.reddit.com/r/alitabattleangel/comments/bnfxsd/alita_battle_angel_on_github">
            GitHub reddit Post
          </a>
        </div>

        <h3>
          Some awesome YouTube channels
        </h3>
        <div class="f-row">
          <a class="link-icon youtube" href="https://www.youtube.com/channel/UCqeFRqda27r7IZfUsxkg1hw">
            <img
              src="https://yt3.ggpht.com/a/AGF-l7-xOfA8jBDZ9nqEKBzVU4mUL4lio1_KOEe_hA=s288-mo-c-c0xffffffff-rj-k-no"
              alt="Doc Ido youtube channel"
            >
            <span>
              Doc Ido
            </span>
          </a>

          <a class="link-icon youtube" href="https://www.youtube.com/channel/UCiE0u9MQFw1lFn7PWqgA9DQ">
            <img
              src="https://yt3.ggpht.com/a/AGF-l786iPLHqoZlr3QfKAoK7-KqoHqGJI5jRciemA=s288-mo-c-c0xffffffff-rj-k-no"
              alt="GenX Strikes Back youtube channel"
            >
            <span>
              GenX Strikes Back
            </span>
          </a>

          <a class="link-icon youtube" href="https://www.youtube.com/channel/UCRtAIk7o92YXDcbxgKk8rMQ">
            <img
              src="https://yt3.ggpht.com/a/AGF-l78nSy6MUviVOtXqz89Z3n50jCN3U14TKRFaYg=s288-mo-c-c0xffffffff-rj-k-no"
              alt="TheLast300 youtube channel"
            >
            <span>
              TheLast300
            </span>
          </a>

          <a class="link-icon youtube" href="https://www.youtube.com/channel/UC1jFLGc_OVIcAOaMKOXYxVw">
            <img
              src="https://yt3.ggpht.com/a/AGF-l78ZVHTHuQaFRE5n6fLbT4pzL3sNTY_TYieDaA=s288-mo-c-c0xffffffff-rj-k-no"
              alt="StarOfElyon youtube channel"
            >
            <span>
              StarOfElyon
            </span>
          </a>

          <a class="link-icon youtube" href="https://www.youtube.com/channel/UCTLIhUpHPyp48jG8WPU36UA">
            <img
              src="https://yt3.ggpht.com/a/AGF-l79_pRb63gbxOKzYQOZu7rktGnq_znJ8XjWH8A=s288-mo-c-c0xffffffff-rj-k-no"
              alt="Installation Seven youtube channel"
            >
            <span>
              Installation Seven
            </span>
          </a>

          <a class="link-icon youtube" href="https://www.youtube.com/channel/UCuya22CZB8CVdM_osO2i9zw">
            <img
              src="https://yt3.ggpht.com/a/AGF-l79-ghLLnhlC7pJH8rDmdMWNSmaiTNi5qrcGzA=s288-mo-c-c0xffffffff-rj-k-no"
              alt="JAYA nice day youtube channel"
            >
            <span>
              JAYA nice day
            </span>
          </a>
        </div>

        <h3>
          And lot of other resources & links on
        </h3>
        <a class="link-icon youtube" href="https://www.alita-index.com" target="_blank">
          <div class="img-c">
            <img src="https://www.alita-index.com/static/img/logo.svg" alt="Alita index">
          </div>
          <span>
            ALITA INDEX
          </span>
        </a>
      </div>
    </article>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      petition: {
        signatureCount: {
          total: '00000'
        }
      }
    }
  },
  mounted() {
    fetch('https://ewcms.org/alita-battle-angel/petition.php').then((res) => {
      return res.json()
    }).then((data) => {
      this.petition = data.petition
    })
  }
}
</script>

<style lang="scss">
  .banner {
    h1 {
      text-align: center;
    }
  }
</style>
