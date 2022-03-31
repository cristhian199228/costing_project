import axios from 'axios'
import router from "../../router";

const state = {
  dialog: false,
}

const getters = {
  getDialog: state => state.dialog,
  getValidSymptoms: state => symptoms => {
    for (const prop in symptoms) {
      if (prop !== 'id' && symptoms[prop]) return true
    }
    return false
  }
}

const actions = {
  async store({commit, rootGetters, dispatch, getters}, symptoms){
    if (!getters.getValidSymptoms(symptoms)) {
      return commit('SHOW_ERROR_SNACKBAR', "Debe ingresar por lo menos un síntoma" , { root: true})
    }
    symptoms.attention_id = rootGetters["atenciones/getAttentionId"]
    try {
      const res = await axios.post('api/v1/symptom', symptoms)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
      commit('SHOW_SYMPTOMS_DIALOG', false)
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async update({commit, dispatch, getters}, symptoms) {
    delete symptoms.created_at
    delete symptoms.updated_at
    delete symptoms.attention_id
    if (!getters.getValidSymptoms(symptoms)) {
      return commit('SHOW_ERROR_SNACKBAR', "Debe ingresar por lo menos un síntoma" , { root: true})
    }
    try {
      const res = await axios.put(`api/v1/symptom/${symptoms.id}`, symptoms)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SHOW_SYMPTOMS_DIALOG', false)
      dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async delete({commit, dispatch}, id) {
    try {
      const res = await axios.delete(`api/v1/symptom/${id}`)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
  SHOW_SYMPTOMS_DIALOG(state, show){
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
