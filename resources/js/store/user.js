import Vue from 'vue'
import Axios from 'axios'

import { keyToCamelClase } from '@/extensions/Object+KeyToCamelClase'
import { keyToSnakeClase } from '@/extensions/Object+KeyToSnakeClase'

const ModuleUser = {

  state: {
    token: '',
    messageAuth: '',
    user: {}
  },

  getters: {
    isAuthenticated: state => {
      return state.token !== null && state.token !== ''
    },

    token: state => {
      return state.token
    },

    messageAuth: state => {
      return state.messageAuth
    },

    getUser: state => {
      return state.user
    }
  },

  mutations: {
    setToken: (state, payload) => {
      state.token = payload.token
    },

    deleteToken: (state) => {
      state.token = ''
      Axios.defaults.headers.common['Authorization'] = null
    },

    addTokenToAxios: (state) => {
      Axios.defaults.headers.common['Authorization'] = `Bearer ${state.token}`
    },

    setMessageAuth: (state, payload) => {
      state.messageAuth = payload
    },

    setUser: (state, payload) => {
      state.user = keyToCamelClase(payload.user)
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
            context.dispatch('loadUser')
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

    async updateUser (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/user/update`, keyToSnakeClase(payload))
          .then(response => {
            context.commit('setUser', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async updateAvatar (context, payload) {
      const config = {
        headers: { 'Content-Type': 'multipart/form-data' }
      }
      let formData = new FormData()
      formData.append('avatar', payload['avatar'])

      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/user/update/avatar`, formData, config)
          .then(response => {
            context.commit('setUser', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async updatePassword (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/user/update/password`, keyToSnakeClase(payload))
          .then(response => {
            context.commit('setUser', response.data)
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
    },

    loadUser (context) {
      return new Promise((resolve, reject) => {
        Axios.get(`/api/v1/user`)
          .then(response => {
            context.commit('setUser', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    }
  }

}

export default ModuleUser
