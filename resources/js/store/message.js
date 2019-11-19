import Vue from 'vue'

const ModuleMessage = {
  mutations: {
    setNotySuccess: (state, response) => {
      if (window.innerWidth <= 768) return

      const statusCode = response.status
      if (statusCode === 201 || statusCode === 200) {
        Vue.noty.success(response.data.message)
      }
    },

    setNotyError: (state, error) => {
      const statusCode = error.response.status
      if (statusCode === 401) {
        Vue.noty.error(error.response.data.message)
        return
      }
      if (statusCode === 403) {
        Vue.noty.error(error.response.data.message)
        return
      }
      if (statusCode === 404) {
        Vue.noty.error(error.response.data.message)
        return
      }
      if (statusCode === 422) {
        const validationErrors = error.response.data.errors
        if (validationErrors) {
          for (let prop in validationErrors) {
            Vue.noty.error(validationErrors[prop])
          }
        }
        return
      }
      console.error(error)
    }
  }
}

export default ModuleMessage
