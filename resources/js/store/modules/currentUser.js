import axios from 'axios'
import router from "../../router";

const state = {
  user: null,
}

const getters = {
  getUser: state => state.user,
  getRole: (state, getters) => {
    if (!getters.getUser) return 'Invitado'
    if (getters.getUser && getters.getUser.isAdmin) return 'Administrador'
    return 'Paciente'
  },
  getName: (state, getters) => {
    if (getters.getUser) return `${getters.getUser.nombres} ${getters.getUser.apellido_paterno}`
    return 'Invitado'
  },
  isAdmin: (state, getters) => {
    if (getters.getUser) return getters.getUser.isAdmin
  }
}

const actions = {
  async login({commit}, params) {
    try {
      const res = await axios.post('/api/v1/login', params)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message , { root: true})
      commit('SET_USER', await res.data.paciente)
      await router.push('/')
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message , { root: true})
    }
  },
  async logout ({ commit }) {
    await commit('LOGOUT_USER');
    await router.push('/login');
  }
}

const mutations = {
  SET_USER(state, user) {
    state.user = user
    window.localStorage.setItem('user', JSON.stringify(user));
  },
  LOGOUT_USER (state) {
    state.user = null
    window.localStorage.removeItem('user')
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
