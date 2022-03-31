import axios from 'axios'
import router from "../../router";

const state = {
  data: {},
  attention_id: null,
  filters: {
    fechas: [new Date().toISOString().substr(0, 10), new Date().toISOString().substr(0, 10)],
    buscar: null,
    sintomas: null,
    antecedentes: null,
    et: null,
    lt: null,
    dc: null,
  },
  dialog: {
    sintomas: false,
    antecedentes: false,
    contactos: false,
  }
}

const getters = {
  getAtenciones: state => state.data.data,
  getAttentionId: state => state.attention_id,
  currentPage: state => state.data.current_page,
  getFilters: (state) => state.filters,
  getDialogSintomas: state => state.dialog.sintomas,
  getDialogAntecedentes: state => state.dialog.antecedentes,
  getDialogContactosDirectos: state => state.dialog.contactos,
}

const actions = {
  getAtencionesPaciente: {
    root: true,
    async handler ({commit, rootGetters, getters}) {
      const config = {
        params: {
          page: getters.currentPage,
        }
      }
      try {
        const res = await axios.get(`api/v1/attention/${rootGetters["currentUser/getUser"].idpacientes}`, config)
        commit('SET_DATA', await res.data)
      } catch (e) {
        console.error(e.response)
      }
    },
  },
  getAtencionesAdministrador: {
    root: true,
    async handler ({commit, getters}) {
      const config = {
        params: {
          page: getters.currentPage,
          fecha_inicio: getters.getFilters.fechas[0],
          fecha_fin: getters.getFilters.fechas[1],
          buscar: getters.getFilters.buscar,
          sintomas: getters.getFilters.sintomas,
          antecedentes: getters.getFilters.antecedentes,
          et: getters.getFilters.et,
          lt: getters.getFilters.lt,
          dc: getters.getFilters.dc,
        }
      }
      try {
        const res = await axios.get(`api/v1/attention`, config)
        commit('SET_DATA', await res.data)
      } catch (e) {
        console.error(e.response)
      }
    },
  },
  async storeAtencion({commit, dispatch,rootGetters}) {
    try {
      const res = await axios.post(`api/v1/attention`, rootGetters["currentUser/getUser"])
      const data = await res.data
      //commit('AGREGAR_ATENCION', await data.data)
      commit('SHOW_SUCCESS_SNACKBAR', await data.message , { root: true})
      dispatch('getAtencionesPaciente', null, {root: true})
    }catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
  SET_DATA (state, data) {
    state.data = data
  },
  AGREGAR_ATENCION (state, data) {
    const atenciones = state.data.data
    if (atenciones.length > 0) atenciones.push(data)
  },
  SET_ATTENTION_ID(state, id){
    state.attention_id = id
  },
  SET_CURRENT_PAGE(state, page) {
    state.data.current_page = page;
  },
  SET_FECHAS_FILTER(state, fechas) {
    state.filters.fechas = fechas
  },
  SET_BUSCAR_FILTER(state, val) {
    state.filters.buscar = val
  },
  SET_SINTOMAS_FILTER(state, val) {
    state.filters.sintomas = val
  },
  SET_ANTECEDENTES_FILTER(state, val) {
    state.filters.antecedentes = val
  },
  SET_ET_FILTER (state, val) {
    state.filters.et = val
  },
  SET_LT_FILTER (state, val) {
    state.filters.lt = val
  },
  SET_DIRECT_CONTACTS_FILTER (state, val) {
    state.filters.dc = val
  },
  SHOW_VER_ANTECEDENTES_DIALOG(state, show) {
    state.dialog.antecedentes = show
  },
  SHOW_VER_SINTOMAS_DIALOG(state, show) {
    state.dialog.sintomas = show
  },
  SHOW_VER_CONTACTOS_DIRECTOS_DIALOG(state, show) {
    state.dialog.contactos = show
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
