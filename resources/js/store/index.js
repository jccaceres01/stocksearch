import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    load: false,
    errors: null
  },
  mutations: {
    setLoad(state, value) {
      state.load = value
    },
    // Set errors mutation
    setErrors(state, value) {
      state.errors = value
    }
  },
  getters: {
    getLoad(state) {
      return state.loading
    }
  },
  actions: {
    toggleLoad(value) {
      commit('setLoad', value)
    },
    // Clear Errors state
    clearErrors({commit}) {
      commit('setErrors', null) 
    }
  }
});

export default store;
