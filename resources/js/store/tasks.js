import Axios from 'axios'

const ModuleTask = {

  state: {
    tasks: [],
    newTaskText: '',
    pageTasks: []
  },

  getters: {
    getNewTaskText: state => {
      return state.newTaskText
    },

    allTasks: state => {
      return state.tasks
    },

    pageTasks: state => {
      return state.pageTasks
    }
  },

  mutations: {
    setTasks: (state, payload) => {
      const tasks = payload.tasks
      state.tasks.push(...tasks.data)
    },

    clearTasks: (state) => {
      state.tasks = []
      state.pageTasks = []
    },

    setPageTasks: (state, payload) => {
      const tasks = payload.tasks
      state.pageTasks = tasks.data
      state.tasks.push(...tasks.data)
    },

    addTask: (state, payload) => {
      const task = payload.task
      state.tasks.push(task)
    },

    updateTask: (state, payload) => {
      const task = payload.task
      const index = state.tasks.findIndex(elem => elem.id === task.id)
      if (index !== -1) {
        state.tasks.splice(index, 1, task)
      }
    },

    deleteTask: (state, payload) => {
      const task = payload.task
      const index = state.tasks.findIndex(elem => elem.id === task.id)
      if (index !== -1) {
        state.tasks.splice(index, 1)
      }
    },

    updateNewTaskText: (state, payload) => {
      state.newTaskText = payload
    }
  },

  actions: {
    setTasks (context) {
      return new Promise((resolve, reject) => {
        Axios.get(`/api/v1/tasks`, {
          params: {
            page: 1
          }
        })
          .then(response => {
            context.commit('clearTasks')
            context.commit('setTasks', response.data)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async setPageTasks (context, page) {
      return new Promise((resolve, reject) => {
        Axios.get(`/api/v1/tasks`, {
          params: {
            page: page
          }
        })
          .then(response => {
            context.commit('setPageTasks', response.data)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async addTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/tasks`, payload)
          .then(response => {
            context.commit('addTask', response.data)
            context.commit('setNotySuccess', response)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async updateTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/tasks/${payload.id}`, payload)
          .then(response => {
            context.commit('updateTask', response.data)
            context.commit('setNotySuccess', response)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async deleteTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.delete(`/api/v1/tasks/${payload.id}`)
          .then(response => {
            context.commit('deleteTask', response.data)
            context.commit('setNotySuccess', response)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async deleteCompletedTasks (context) {
      return new Promise((resolve, reject) => {
        Axios.delete(`/api/v1/tasks/destroy/completed/`)
          .then(response => {
            if (response.data.tasks > 0) {
              context.dispatch('setTasks')
              context.commit('setNotySuccess', response)
            }
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    },

    async checkTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/tasks/${payload.id}/check/`, payload)
          .then(response => {
            context.commit('updateTask', response.data)
            context.commit('setNotySuccess', response)
            resolve(response)
          }).catch(error => {
            context.commit('setNotyError', error)
            reject(error)
          })
      })
    }
  }
}

export default ModuleTask
