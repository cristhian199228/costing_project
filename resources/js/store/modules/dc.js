import axios from 'axios'
import router from "../../router";

const state = {
  directContacts: [],
  dialog: false,
}

const getters = {
  getDc: state => state.directContacts,
  getDialog: state => state.dialog,
}

const actions = {
  async getDirectContacts({commit, rootGetters}) {
    try {
      const res = await axios.get(`api/v1/dc/${rootGetters["atenciones/getAttentionId"]}`)
      commit('SET_DIRECT_CONTACTS', res.data)
    } catch (e) {
      console.error(e.response)
    }
  },
  async store({commit, dispatch, rootGetters}, dc) {
    dc.attention_id = rootGetters["atenciones/getAttentionId"]
    try {
      const res = await axios.post('api/v1/dc', dc)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SHOW_SAVE_DIRECT_CONTACT_DIALOG', false)
      return await dispatch('getDirectContacts')
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async update({commit, dispatch}, dc) {
    try {
      const res = await axios.put(`api/v1/dc/${dc.id}`, dc)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SHOW_SAVE_DIRECT_CONTACT_DIALOG', false)
      return await dispatch('getDirectContacts')
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async delete({commit, dispatch}, id) {
    try {
      const res = await axios.delete(`api/v1/dc/${id}`)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getDirectContacts')
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
  SET_DIRECT_CONTACTS(state, data) {
    state.directContacts = data
  },
  SHOW_SAVE_DIRECT_CONTACT_DIALOG(state, show) {
    state.dialog = show
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
