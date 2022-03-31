import axios from 'axios'

const state = {
}

const getters = {
}

const actions = {
  async store({commit, dispatch}, params) {
    console.log(params)
    try {
      const res = await axios.post('api/v1/binnacle', params)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getTracing', params.tracing_id, { root: true })
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
