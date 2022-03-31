import axios from 'axios'
import router from "../../router";

const state = {
  dialog: false,
}

const getters = {
  getDialog: state => state.dialog,
  getValidAntecedentes: state => eh => {
    for (const prop in eh) {
      if (prop !== 'id' && eh[prop]) return true
    }
    return false
  }
}

const actions = {
  async store({commit, rootGetters, dispatch, getters}, eh){
    if (!getters.getValidAntecedentes(eh)) {
      return commit('SHOW_ERROR_SNACKBAR', "Debe ingresar por lo menos un antecedente" , { root: true})
    }
    eh.attention_id = rootGetters["atenciones/getAttentionId"]
    try {
      const res = await axios.post('api/v1/eh', eh)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SHOW_EH_DIALOG', false)
      return await dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async update({commit, dispatch, getters}, eh) {
    delete eh.created_at
    delete eh.updated_at
    delete eh.attention_id
    if (!getters.getValidAntecedentes(eh)) {
      return commit('SHOW_ERROR_SNACKBAR', "Debe ingresar por lo menos un antecedente" , { root: true})
    }
    try {
      const res = await axios.put(`api/v1/eh/${eh.id}`, eh)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SHOW_EH_DIALOG', false)
      return await dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async delete({commit, dispatch}, id) {
    try {
      const res = await axios.delete(`api/v1/eh/${id}`)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
  SHOW_EH_DIALOG(state, show){
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
