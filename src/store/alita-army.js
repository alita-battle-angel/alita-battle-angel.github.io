export const state = () => ({
  data: 0
})
export const mutations = {
  set(state, payload) {
    Object.assign(state, payload)
  }
}
