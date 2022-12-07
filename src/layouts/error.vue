<template>
  <div class="__nuxt-error-page">
    <div class="error">
      <i class="material-icons">error</i>

      <h1 class="title">
        {{ statusCode }}
      </h1>
      <h2 class="title">
        {{ message }}
      </h2>
      <p v-if="statusCode === 404" class="description">
        <NuxtLink class="error-link" to="/">
          Go back to Home
        </NuxtLink>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  layout: 'error',
  head () {
    return {
      title: this.message,
      meta: [
        {
          name: 'viewport',
          content: 'width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'
        }
      ]
    }
  },
  computed: {
    statusCode () {
      return (this.error && this.error.statusCode) || 500
    },
    message () {
      return this.error.message || ''
    }
  }
}
</script>

<style lang="scss">
  .__nuxt-error-page {
    padding: 1rem;
    color: #47494E;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-family: sans-serif;
    font-weight: 100 !important;
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
    -webkit-font-smoothing: antialiased;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    .error {
      > i {
        font-size: 6em;
        color: #fff;
      }
    }
  }

</style>
