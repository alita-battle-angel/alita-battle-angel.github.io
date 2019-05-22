<template>
  <div class="font-digit">
    <span>{{ days }} D</span>
    <span>{{ hours }} H</span>
    <span>{{ minutes }} M</span>
    <span>{{ seconds }} S</span>
  </div>
</template>

<script>
const endDate = new Date(2019, 6, 9, 0, 0, 0).getTime()
// const endDate = new Date().getTime() + 5000

export default {
  data: () => {
    return {
      timespan: 1
    }
  },
  computed: {
    seconds() {
      const minutesms = this.timespan % (60)
      return Math.floor(minutesms)
    },
    minutes() {
      const hoursms = this.timespan % (60 * 60)
      return Math.floor((hoursms) / (60))
    },
    hours() {
      const daysms = this.timespan % (24 * 60 * 60)
      return Math.floor((daysms) / (60 * 60))
    },
    days() {
      return Math.floor(this.timespan / (24 * 60 * 60))
    },
    remain() {
      const days = Math.floor(this.timespan / (24 * 60 * 60))
      const daysms = this.timespan % (24 * 60 * 60)
      const hours = Math.floor((daysms) / (60 * 60))
      const hoursms = this.timespan % (60 * 60)
      const minutes = Math.floor((hoursms) / (60))
      const minutesms = this.timespan % (60)
      const sec = Math.floor(minutesms)
      return `${days} D ${hours} H ${minutes} M ${sec} S`
    }
  },
  created() {
    this.calculate()
    this.interval = setInterval(() => {
      this.calculate()
    }, 1000)
  },
  destroyed() {
    clearInterval(this.interval)
  },
  methods: {
    calculate() {
      const startDate = new Date().getTime()
      const diff = endDate - startDate
      this.timespan = Math.floor(diff / 1000)
      if (this.timespan <= 0) {
        clearInterval(this.interval)
        // do stuff here
      }
    }
  }
}
</script>

<style lang="scss">
  .font-digit {
    font-size: 40px;
    text-transform: uppercase;
    display: flex;
    justify-content: center;
    color: #fff;

    > span {
      text-align: right;
      width: 140px;
    }
  }
</style>
