import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    tasks: []
  },

  getters: {
    GET_TASKS: state => {
      return state.tasks
    },

    ACTUAL_TASKS: state => {
      return state.tasks.filter(t => !t.done)
    }
  },

  mutations: {
    SET_TASKS: (state, payload) => {
      state.tasks = payload
    },

    ADD_TASK: (state, payload) => {
      state.tasks.push(payload)
    },

    UPDATE_TASK: (state, payload) => {
      const task = payload
      const index = state.tasks.findIndex(elem => elem.id === task.id)
      if (index !== -1) {
        state.tasks.splice(index, 1, task)
      }
    },

    DELETE_TASK: (state, payload) => {
      const task = payload
      const index = state.tasks.findIndex(elem => elem.id === task.id)
      if (index !== -1) {
        state.tasks.splice(index, 1)
      }
    }
  },

  actions: {
    setTasks: async (context) => {
      Axios.get(`/api/v1/tasks`)
        .then(response => {
          context.commit('SET_TASKS', response.data)
        }).catch(error => {
          console.error(error)
        })
    },

    addTask: async (context, payload) => {
      Axios.post(`/api/v1/tasks`, payload)
        .then(response => {
          context.commit('ADD_TASK', response.data)
        }).catch(error => {
          console.error(error)
        })
    },

    updateTask: async (context, payload) => {
      Axios.patch(`/api/v1/tasks/${payload.id}`, payload)
        .then(response => {
          context.commit('UPDATE_TASK', response.data)
        }).catch(error => {
          console.error(error)
        })
    },

    deleteTask: async (context, payload) => {
      Axios.delete(`/api/v1/tasks/${payload.id}`)
        .then(response => {
          context.commit('DELETE_TASK', response.data)
        }).catch(error => {
          console.error(error)
        })
    },

    deleteCompletedTasks: async (context) => {
      Axios.delete(`/api/v1/tasks/destroy_completed/`)
        .then(response => {
          if (response.data > 0) {
            context.dispatch('setTasks')
          }
        }).catch(error => {
          console.error(error)
        })
    },

    checkTask: async (context, payload) => {
      Axios.patch(`/api/v1/tasks/${payload.id}/check/`, payload)
        .then(response => {
          context.commit('UPDATE_TASK', response.data)
        }).catch(error => {
          console.error(error)
        })
    }
  }
})
