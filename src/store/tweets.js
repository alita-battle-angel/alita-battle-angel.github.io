export const state = () => ({
  data: []
})
export const mutations = {
  set(state, payload) {
    state.data = payload
  }
}
