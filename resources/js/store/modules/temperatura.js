import axios from 'axios'

const state = {
  dialog: false,
  entero: 36,
  decimal: 6,
  temperature_id: null,
  temp_type: null,
}

const getters = {
  getDialog: state => state.dialog,
  getEntero: state => state.entero,
  getDecimal: state => state.decimal,
  getTemperatureId: state => state.temperature_id,
  getTempType: state => state.temp_type,
  getTemperatureValue: (state, getters) => Number(`${getters.getEntero}.${getters.getDecimal}`),
}

const actions = {
  async store({commit, rootGetters, dispatch, getters}) {
    const data = {
      attention_id: rootGetters["atenciones/getAttentionId"],
      value: getters.getTemperatureValue
    }
    try {
      const res = await axios.post(`api/v1/${getters.getTempType}`, data)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
      commit('SHOW_TEMPERATURE_DIALOG', false)
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async update({commit, dispatch, getters}) {
    const data = {
      value: getters.getTemperatureValue
    }
    try {
      const res = await axios.put(`api/v1/${getters.getTempType}/${getters.getTemperatureId}`, data)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
      commit('SHOW_TEMPERATURE_DIALOG', false)
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async delete({commit, dispatch, getters}) {
    try {
      const res = await axios.delete(`api/v1/${getters.getTempType}/${getters.getTemperatureId}`)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      dispatch('getAtencionesPaciente', null, { root: true})
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  }
}

const mutations = {
  SHOW_TEMPERATURE_DIALOG(state, show){
    state.dialog = show
  },
  ADD_ENTERO (state) {
    state.entero++
  },
  SUBTRACT_ENTERO (state) {
    state.entero--
  },
  ADD_DECIMAL (state) {
    state.decimal++
  },
  SUBTRACT_DECIMAL (state) {
    state.decimal--
  },
  SET_ENTERO (state, value) {
    state.entero = value
  },
  SET_DECIMAL (state, value) {
    state.decimal = value
  },
  SET_TEMPERATURE (state, temperature) {
    const temp = temperature.value
    const arrTemp = String(temp).split('.')
    state.temperature_id = temperature.id
    state.entero = Number(arrTemp[0])
    state.decimal = arrTemp.length > 0 ? Number(arrTemp[1]) : 0
  },
  SET_TEMP_TYPE(state, type) {
    state.temp_type = type
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
