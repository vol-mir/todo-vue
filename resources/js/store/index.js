import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'

import createPersistedState from 'vuex-persistedstate'

import tasks from '@modules/tasks.js'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    tasks
  },

  state: {
    token: '',
    messageAuth: ''
  },

  getters: {
    isAuthenticated (state) {
      return state.token !== null && state.token !== ''
    },

    token (state) {
      return state.token
    },

    messageAuth (state) {
      return state.messageAuth
    }
  },

  mutations: {
    setToken (state, payload) {
      state.token = payload.token
    },

    deleteToken (state) {
      state.token = ''
      Axios.defaults.headers.common['Authorization'] = null
    },

    addTokenToAxios (state) {
      Axios.defaults.headers.common['Authorization'] = `Bearer ${state.token}`
    },

    setMessageAuth (state, payload) {
      state.messageAuth = payload
    }
  },

  actions: {
    async signup (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/signup`, payload)
          .then(response => {
            Vue.localStorage.set('signupEmail', payload.email)
            Vue.localStorage.set('signupPassword', payload.password)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async signin (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/signin`, payload)
          .then(response => {
            const token = {
              token: response.data.access_token
            }
            context.commit('setToken', token)
            context.commit('addTokenToAxios', token)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async logout (context) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/logout`)
          .then(response => {
            context.commit('deleteToken')
            resolve(response)
          }).catch(error => {
            const statusCode = error.response.status
            if (statusCode === 401 || statusCode === 400) {
              context.commit('deleteToken')
              return
            }
            console.error(error)
            reject(error)
          })
      })
    },

    async resetPassword (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/password/reset`, payload)
          .then(response => {
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async createPassword (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/password/create`, payload)
          .then(response => {
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    init (context) {
      context.commit('addTokenToAxios')

      Axios.interceptors.response.use((response) => {
        return response
      }, (error) => {
        const statusCode = error.response.status
        if (statusCode === 401 || statusCode === 400) {
          context.commit('deleteToken')
        }
        return Promise.reject(error)
      })
    }
  },

  plugins: [createPersistedState()]
})

export default store
