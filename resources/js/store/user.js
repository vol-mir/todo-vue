import Axios from 'axios'

import { keyToCamelClase } from '@/extensions/Object+KeyToCamelClase'
import { keyToSnakeClase } from '@/extensions/Object+KeyToSnakeClase'

const ModuleUser = {

  state: {
    user: {}
  },

  getters: {
    getUser: state => {
      return state.user
    }
  },

  mutations: {
    setUser: (state, payload) => {
      state.user = keyToCamelClase(payload.user)
    }
  },

  actions: {
    async loadUser (context) {
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
    }
  }

}

export default ModuleUser
