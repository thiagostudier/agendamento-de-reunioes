import Vue from 'vue'
import Vuex from 'vuex'

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
          //GUARDAR SESSÃO DO USUÁRIO
          localStorage.setItem('auth', JSON.stringify(data.user));
        },
        logout(state) {
          localStorage.clear();
          state.token = false;
          state.user = [];
        },
      },
      actions: {
        login({ commit }, data) {
          commit('login', { data })
        },
        logout({ commit }) {
          commit('logout')
        }
      },
});

export default store;