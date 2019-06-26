export const state = () => ({
  data: [],
  page: 1,
  total_pages: 1,
  fetching: false
})
export const mutations = {
  set(state, payload) {
    Object.assign(state, payload)
  }
}
