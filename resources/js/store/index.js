import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    tasks: []
  },

  getters: {
    GET_TASKS: state => {
      return state.tasks
    },

    NOT_DONE_TASKS: state => {
      return state.tasks.filter(t => !t.done)
    }
  },

  mutations: {
    SET_TASKS: (state) => {
      let data = localStorage.getItem('todos')
      if (data != null) {
        state.tasks = JSON.parse(data)
      }
    },

    ADD_TASK: (state, payload) => {
      state.tasks.push({
        action: payload,
        done: false
      })
      localStorage.setItem('todos', JSON.stringify(state.tasks))
    },

    DELETE_COMPLETED_TASKS: (state) => {
      state.tasks = state.tasks.filter(t => !t.done)
      localStorage.setItem('todos', JSON.stringify(state.tasks))
    }
  },

  actions: {
    setTasks: async (context) => {
      context.commit('SET_TASKS')
    },

    addTask: async (context, payload) => {
      context.commit('ADD_TASK', payload)
    },

    deleteCompletedTasks: async (context) => {
      context.commit('DELETE_COMPLETED_TASKS')
    }
  }
})
