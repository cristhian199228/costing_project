import Vue from 'vue'
import Vuex from 'vuex'
import currentUser from "./modules/currentUser";
import atenciones from "./modules/atenciones";
import temperatura from "./modules/temperatura";
import sintomas from "./modules/sintomas";
import seguimientos from "./modules/seguimientos";
import binnacle from "./modules/binnacle";
import antecedentes from "./modules/antecedentes";
import dc from "./modules/dc";
import paises from "./modules/paises";

Vue.use(Vuex)

const state = {
  snackbar: {
    message: null,
    color: null,
    show: false,
  },
  pacientes: [],
}

const getters = {
  getPacientes: state => state.pacientes
}

const mutations  = {
  SHOW_SUCCESS_SNACKBAR(state, message){
    state.snackbar.color = 'success'
    state.snackbar.message = message
    state.snackbar.show = true
  },
  SHOW_ERROR_SNACKBAR(state, message){
    state.snackbar.color = 'error'
    state.snackbar.message = message
    state.snackbar.show = true
  },
  SET_PACIENTES (state, data) {
    state.pacientes = data
  }
}

const actions = {
  async searchPacientes ({commit}, buscar){
    const config = {
      params: { buscar }
    }
    try {
      const res = await axios.get('/api/v1/search/patient', config)
      commit('SET_PACIENTES', await res.data)
    } catch (e) {
      console.error(e.response)
    }
  }
}

const modules = {
  currentUser,
  atenciones,
  temperatura,
  sintomas,
  seguimientos,
  binnacle,
  antecedentes,
  dc,
  paises,
}

const store = new Vuex.Store({
  state,
  getters,
  mutations,
  actions,
  modules
})

export default store