<template>
  <div class="pagination">
    <NuxtLink :class="{button:true , disabled:can_previous}" :to="previousUrl">
      <i class="material-icons">keyboard_arrow_left</i>
    </NuxtLink>
    <span>
      {{ page }} / {{ totalPages }}
    </span>
    <NuxtLink :class="{button:true , disabled: can_next}" :to="nextUrl">
      <i class="material-icons">keyboard_arrow_right</i>
    </NuxtLink>
  </div>
</template>
<script>
export default {
  props: {
    page: {
      type: Number,
      default: 1
    },
    totalPages: {
      type: Number,
      default: 1
    },
    previousUrl: {
      type: String,
      default: null
    },
    nextUrl: {
      type: String,
      default: null
    }
  },
  computed: {
    can_previous () {
      return this.page <= 1
    },
    can_next () {
      return this.totalPages <= this.page
    }
  },
  methods: {}
}
</script>
<style lang="scss">
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    font-family: 'Inconsolata', monospace;

    > button, .button {
      display: flex;
      width: 84px;
      height: 42px;
      padding: 5px 0;
      margin: 0 15px;
      vertical-align: middle;
      align-items: center;
      justify-content: center;
    }

    > span {
      width: 120px;
      text-align: center;
      font-weight: bold;
      white-space: nowrap;
    }
  }
</style>
