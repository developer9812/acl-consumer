import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
  state : {
    intendedPath: '/',
    loginStatus: false,
    permissions: []
  },
  getters: {
    intendedPath: state => state.intendedPath,
    loginStatus: state => state.loginStatus,
    permissions: state => state.permissions
  },
  mutations: {
    setIntendedPath: (state, payload) => {
      state.intendedPath = payload;
    },
    setLoginStatus: (state, payload) => {
      state.loginStatus = payload;
    },
    setPermissions: (state, payload) => {
      state.permissions = payload;
    }
  }
})

export default store;
