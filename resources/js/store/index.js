import Vue from 'vue'
import Vuex from 'vuex'

import createPersistedState from 'vuex-persistedstate'
import createMutationsSharer from 'vuex-shared-mutations'

import tasks from '@modules/tasks.js'
import user from '@modules/user.js'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    tasks,
    user
  },

  plugins: [
    createPersistedState(),
    createMutationsSharer({ predicate: ['updateNewTaskText'] })
  ]
})

export default store
