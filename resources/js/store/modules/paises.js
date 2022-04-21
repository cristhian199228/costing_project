import axios from 'axios'
import router from "../../router";

const state = {
  dialog_pais: false,
  data: {},
  id_pais_editado:null,
  pais_editado:{}
}

const getters = {
  getPaises: state => state.data.data,
  getPaisEditado: state => state.pais_editado,
}

const actions = {
  initPaises: {
    async handler({ commit, rootGetters, getters }) {
      const config = {
        params: {
          page: getters.currentPage,
        }
      }
      try {
        const res = await axios.get(`api/v1/attention/${rootGetters["currentUser/getUser"].idpacientes}`, config)
        commit('SET_DATA', await res.data)
        console.log('hola')
      } catch (e) {
        console.error(e.response)
      }
    },
  },
  initPaisEditado: {
    async handler({ commit, rootGetters, getters }) {
   
      try {
        const res = await axios.get(`api/v1/attention/${rootGetters["currentUser/getUser"].idpacientes}`, config)
        commit('SET_DATA', await res.data)
      } catch (e) {
        console.error(e.response)
      }
    },
  },
}

const mutations = {
  SET_DATA(state, data) {
    state.data = data
  },
  SHOW_PAIS_DIALOG (state, show) {
    state.dialog_pais = show
  },
  SET_ID_PAIS_EDITADO(state, data){
    state.id_pais_editado = data
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
