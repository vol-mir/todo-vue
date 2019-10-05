import Axios from 'axios'

const ModuleTask = {

  state: {
    tasks: [],
    newTaskText: ''
  },

  getters: {
    getNewTaskText: state => {
      return state.newTaskText
    },

    allTasks: state => {
      return state.tasks
    },

    doneTasks: state => {
      return state.tasks.filter(t => !t.done)
    }
  },

  mutations: {
    setTasks: (state, payload) => {
      state.tasks = payload.tasks
    },

    addTask: (state, payload) => {
      state.tasks.push(payload.task)
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
    async setTasks (context) {
      return new Promise((resolve, reject) => {
        Axios.get(`/api/v1/tasks`)
          .then(response => {
            context.commit('setTasks', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async addTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.post(`/api/v1/tasks`, payload)
          .then(response => {
            context.commit('addTask', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async updateTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/tasks/${payload.id}`, payload)
          .then(response => {
            context.commit('updateTask', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async deleteTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.delete(`/api/v1/tasks/${payload.id}`)
          .then(response => {
            context.commit('deleteTask', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
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
            }
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    },

    async checkTask (context, payload) {
      return new Promise((resolve, reject) => {
        Axios.patch(`/api/v1/tasks/${payload.id}/check/`, payload)
          .then(response => {
            context.commit('updateTask', response.data)
            resolve(response)
          }).catch(error => {
            console.error(error)
            reject(error)
          })
      })
    }
  }

}

export default ModuleTask
