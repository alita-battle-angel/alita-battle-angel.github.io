<template>
  <div class="page">
    <div class="page-row">
      <div class="box">
        <header>
          <h1 class="title">
            Merchandise
          </h1>
          <h2>
            This page is work-in-progress
          </h2>
        </header>
        <main class="f-col">
          <iframe
            class="youtube-widget"
            src="https://www.youtube.com/embed/qWYXMDiCRD8?controls=1"
            frameborder="0"
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          />
          <h3>Digital release available on Jul 09, 2019</h3>

          <a
            class="link-icon large"
            href="https://www.amazon.com/Alita-Battle-Angel-Rosa-Salazar/dp/B07NKSKS39/"
            target="_blank"
          >
            <img
              src="data:image/png;base64,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"
              alt="prime video logo"
            >
            <span>
              Alita: Battle Angel
            </span>
          </a>
        </main>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  head: {
    title: 'Merchandise | I Do Not Stand by in The Presence of Evil',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content: 'Browse all Alita\'s merchandise'
      }
    ]
  },
  components: {}
}
</script>

<style>
  .countdown-to-release {
    margin-bottom: 30px;
  }
</style>
