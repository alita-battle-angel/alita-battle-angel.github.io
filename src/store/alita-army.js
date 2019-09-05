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
  }
}
