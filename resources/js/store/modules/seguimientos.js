import axios from 'axios'
import router from "../../router";

const state = {
  data: {},
  planilla: {},
  dialog: false,
  binnacles: [],
  filters: {
    estado: 0,
    fechas: [new Date().toISOString().substr(0, 10), new Date().toISOString().substr(0, 10)],
    buscar: null,
  },
  tracing_id: null,
}

const getters = {
  currentPage: state => state.data.current_page,
  tracings: state => state.data.data,
  tracingsPlanilla: state => state.planilla.data,
  getDialog: state => state.dialog,
  getBinnacles: state => state.binnacles,
  getTracingById: (state, getters) => (id) => getters.tracings.find(t => t.id === id),
  getFilters: (state) => state.filters,
  getTracingId: state => state.tracing_id,
}

const actions = {
  getTracings: {
    root: true,
    async handler ({commit, getters}) {
      const config = {
        params: {
          page: getters.currentPage,
          estado: getters.getFilters.estado,
          fecha_inicio: getters.getFilters.fechas[0],
          fecha_fin: getters.getFilters.fechas[1],
          buscar: getters.getFilters.buscar,
        }
      }
      try {
        const res = await axios.get('api/v1/tracing', config)
        commit('SET_DATA', await res.data)
      } catch (e) {
        console.error(e.response)
      }
    }
  },
  getTracingsPlanilla: {
    root: true,
    async handler ({commit, getters}) {
      const config = {
        params: {
          page: getters.currentPage,
          estado: getters.getFilters.estado,
          fecha_inicio: getters.getFilters.fechas[0],
          fecha_fin: getters.getFilters.fechas[1],
          buscar: getters.getFilters.buscar,
        }
      }
      try {
        const res = await axios.get('api/v1/tracingPlanilla', config)
        commit('SET_DATA_PLANILLA', await res.data)
      } catch (e) {
        console.error(e.response)
      }
    }
  },
  async store({commit, dispatch}, tracing) {
    try {
      const res = await axios.post('api/v1/tracing', tracing)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getTracings', null, {root: true})
      commit('SHOW_SAVE_TRACING_DIALOG', false)
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  getTracing: {
    root: true,
    async handler({commit}, id) {
      try {
        const res = await axios.get(`api/v1/tracing/${id}`)
        commit('SET_BINNACLES', res.data.binnacle)
      } catch (e) {
        console.error(e.response.data)
      }
    },
  }
}

const mutations = {
  SET_DATA (state, data) {
    state.data = data
  },
  SET_DATA_PLANILLA (state, data) {
    state.planilla = data
  },
  SET_CURRENT_PAGE(state, page) {
    state.data.current_page = page;
  },
  SHOW_SAVE_TRACING_DIALOG (state, show) {
    state.dialog = show
  },
  SET_BINNACLES(state, data) {
    state.binnacles = data
  },
  SET_ESTADO_FILTER(state, estado) {
    state.filters.estado = estado
  },
  SET_FECHAS_FILTER(state, fechas) {
    state.filters.fechas = fechas
  },
  SET_BUSCAR_FILTER(state, val) {
    state.filters.buscar = val
  },
  SET_TRACING_ID (state, id) {
    state.tracing_id = id
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
