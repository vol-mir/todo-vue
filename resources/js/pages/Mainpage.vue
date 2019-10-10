<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <card>
            <template slot="header">
              <h5 class="title">Tasks</h5>
            </template>
            <div
              class="row"
              v-if="orderedTasks.length == 0"
              key="tasks-empty"
            >
              <div class="col text-center text-secondary">
                <h5 class="title">Nothing to do. Hurrah!</h5>
              </div>
            </div>
            <div
              v-else
              key="tasks-no-empty"
            >
              <table class="table">
                <tbody>
                <tr
                  v-for="task in orderedTasks"
                  :key="task.id"
                >
                  <td>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input
                          type="checkbox"
                          :value="task.id"
                          :id="task.id"
                          :checked="task.done"
                          @click="checkTask"
                          class="form-check-input"
                        />
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  <td width="80%">
                    <div v-if="editOffset != task.id">
                      {{task.name}}
                    </div>
                    <div v-else>
                      <input
                        type="text"
                        :id = "'item-task-'+task.id"
                        @keydown.enter="updateTask"
                        @keydown.esc="cancelEditingTask"
                        class="form-control"
                        v-model="editTask.name"/>
                    </div>
                  </td>
                  <td class="td-actions text-right">
                    <div
                      style="display: inline-block; text-align: right; width: 100%"
                      v-if="editOffset != task.id"
                    >
                    <button
                      type="button"
                      class="btn-simple btn btn-xs btn-info"
                      @click="startEditingTask(task.id)"
                      v-tooltip.top-center="editTooltip"
                    >
                      <i class="fa fa-edit"></i>
                    </button>
                    <button
                      type="button"
                      class="btn-simple btn btn-xs btn-danger"
                      @click="deleteTask(task)"
                      v-tooltip.top-center="deleteTooltip"
                    >
                      <i class="fa fa-times"></i>
                    </button>
                    </div>
                    <div
                      style="display: inline-block; text-align: right; width: 100%"
                      v-else
                    >
                      <button
                        type="button"
                        class="btn-simple btn btn-xs btn-info"
                        @click="updateTask"
                        v-tooltip.top-center="editTooltip"
                      >
                        <i class="fa fa-plus"></i>
                      </button>
                      <button
                        type="button"
                        class="btn-simple btn btn-xs btn-danger"
                        @click="cancelEditingTask"
                        v-tooltip.top-center="deleteTooltip"
                      >
                        <i class="fa fa-minus"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <hr>

            <div class="row bg-secondary py-2 mt-2 ml-2 mr-2">
              <div class="col-10 text-center text-secondary">
                <input
                  type="text"
                  v-model="newTaskText"
                  class="form-control"
                  @keydown.enter="addNewTask"
                  @keydown.esc="cancelAddingTask"/>
              </div>
              <div class="col-2">
                <button
                  class="btn btn-primary btn-fill "
                  @click="addNewTask"
                >
                  new task
                </button>
              </div>
            </div>

            <div class="row bg-secondary py-2 text-white ml-2 mr-2 ">
              <div class="col text-center">
                <input
                  type="checkbox"
                  v-model="hideCompleted"
                  class="form-check-input"
                />
                <label class="form-check-label font-weight-bold">
                  Hide completed tasks
                </label>
              </div>
              <div class="col text-center">
                <button
                  class="btn btn-sm btn-fill btn-warning"
                  @click="deleteCompletedTasks"
                >Delete Completed</button>
              </div>
            </div>

            <div class="footer">
              <hr>
              <div class="stats">
                <i class="fa fa-history"></i> Updated 3 minutes ago
              </div>
            </div>
          </card>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Mainpage',

  data () {
    return {
      hideCompleted: true,
      editOffset: -1,
      editTask: {},
      editTaskOri: {},
      editTooltip: 'Edit Task',
      deleteTooltip: 'Remove'
    }
  },

  computed: {
    allTasks () {
      return this.$store.getters.allTasks
    },

    doneTasks () {
      return this.$store.getters.doneTasks
    },

    filteredTasks () {
      return this.hideCompleted ? this.doneTasks : this.allTasks
    },

    orderedTasks () {
      return _.orderBy(this.filteredTasks, ['done', 'created_at'], ['asc', 'desc'])
    },

    isAuthenticated () {
      return this.$store.getters.isAuthenticated
    },

    newTaskText: {
      get () {
        return this.$store.getters.getNewTaskText
      },
      set (value) {
        this.$store.commit('updateNewTaskText', value)
      }
    }
  },

  mounted () {
    this.fetchTasks()
  },

  methods: {
    fetchTasks () {
      this.$store.dispatch('setTasks')
    },

    addNewTask () {
      this.$store.dispatch('addTask', {
        'name': this.newTaskText
      })
      this.$store.commit('updateNewTaskText', '')
    },

    cancelAddingTask () {
      this.$store.commit('updateNewTaskText', '')
    },

    deleteCompletedTasks () {
      this.$store.dispatch('deleteCompletedTasks')
    },

    deleteTask (task) {
      this.$store.dispatch('deleteTask', task)
    },

    startEditingTask (idTask) {
      this.editOffset = idTask
      const findTask = this.orderedTasks.find(elem => elem.id === idTask)
      this.editTask = JSON.parse(JSON.stringify(findTask))
      this.editTaskOri = JSON.parse(JSON.stringify(this.editTask))
      this.$nextTick(function () {
        document.getElementById('item-task-' + this.editOffset).focus()
      }.bind(this))
    },

    updateTask () {
      this.$store.dispatch('updateTask', this.editTask)
      this.editOffset = -1
      this.editTaskOri = {}
      this.editTask = {}
    },

    cancelEditingTask () {
      const indexFiltTask = this.orderedTasks.findIndex(elem => elem.id === this.editOffset)
      this.$set(this.orderedTasks, indexFiltTask, this.editTaskOri)
      this.editOffset = -1
      this.editTaskOri = {}
      this.editTask = {}
    },

    checkTask (e) {
      this.$store.dispatch('checkTask', {
        'done': e.target.checked,
        'id': e.target.value
      })
    },

    logout () {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/signin')
        })
    }

  }
}
</script>
