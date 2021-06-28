import Vue from 'vue'
import Vuex from 'vuex'
import { api } from '@/services/api.ts';

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    token: localStorage.getItem('auth') && JSON.parse(localStorage.getItem('auth')).token ? JSON.parse(localStorage.getItem('auth')).token : false,
    user: localStorage.getItem('auth') && JSON.parse(localStorage.getItem('auth')) ? JSON.parse(localStorage.getItem('auth')) : false,
  },
  mutations: {
    login(state,{ data }) {
      state.token = data.user.token
      state.user = data.user
      localStorage.setItem('auth', JSON.stringify(data.user));
    },
    logout(state) {
      localStorage.clear();
      state.token = false;
      state.user = [];
    },
    checkAuth(state){
      var auth = false;
      api.post(`get-me`, {}, {headers: {"Authorization":"Bearer "+state.token}})
      .then(response => {
        // SE O USUÁRIO FOR AUTENTICADO
        auth = true;
      })
      .catch(e => {
        // SE O USUÁRIO NÃO FOR AUTENTICADO
        this.dispatch('logout')
      });
      return auth;
    }
  },
  actions: {
    login({ commit }, data) {
      commit('login', { data })
    },
    logout({ commit }) {
      commit('logout')
    },
    checkAuth({ commit }){
      commit('checkAuth')
    }
  },
});

export default store;