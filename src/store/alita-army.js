export const state = () => ({
  data: [],
  page: 1,
  total_pages: 1,
  fetching: false
})
export const mutations = {
  set (state, payload) {
    const data = payload.data || []
    data.sort((a, b) => {
      const dateA = new Date(a.updated_at)
      const dateB = new Date(b.updated_at)
      return dateB - dateA
    })

    Object.assign(state, payload)
  },
  update (state, payload) {
    state.data.forEach((item) => {
      if (item.screen_name === payload.screen_name) {
        Object.assign(item, payload)
      }
    })
  },
  remove (state, payload) {
    const found = state.data.filter((item) => {
      return item.screen_name === payload.screen_name
    })[0]

    if (found) {
      const index = state.data.indexOf(found)
      state.data.splice(index, 1)
    }
  }
}
