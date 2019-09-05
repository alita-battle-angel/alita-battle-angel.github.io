export const state = () => ({
  data: [],
  page: 1,
  total_pages: 1,
  fetching: false,
  filterBy: null
})

export const mutations = {
  set (state, payload) {
    const data = payload.data || []
    data.sort((a, b) => {
      const dateA = new Date(a.status_created_at)
      const dateB = new Date(b.status_created_at)
      return dateB - dateA
    })

    Object.assign(state, payload)
  }
}
