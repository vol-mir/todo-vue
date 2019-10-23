<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <card>
            <template slot="header">
              <h4 slot="header" class="card-title">Tasks</h4>
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
              class="my-custom-scrollbar table-wrapper-scroll-y"
              id="tasks-list"
              ref="tasksList"
            >
              <table class="table">
                <tbody>
                <infinite-loading
                  slot="append"
                  @infinite="infiniteHandler"
                  direction="top"
                  @distance="1">
                  <div slot="no-more"></div>
                  <div slot="no-results"></div>
                </infinite-loading>
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

            <div class="row py-2 mt-2 ml-2 mr-2">
              <div class="col-12 input-group">
                  <input
                    type="text"
                    v-model="newTaskText"
                    class="form-control"
                    @keydown.enter="addNewTask"
                    placeholder="New tasks..."
                    @keydown.esc="cancelAddingTask"
                    aria-label="New tasks..."
                    style="height:45px; border-color:grey"
                  >
                  <div class="input-group-append">
                    <button
                      class="btn btn-outline-secondary btn-fill"
                      type="button"
                      @click="addNewTask"
                      v-tooltip.top-center="'Add new task'"
                    >
                      Add
                    </button>
                    <button
                      class="btn btn-outline-secondary"
                      type="button"
                      @click="hideComlTask"
                      v-tooltip.top-center="this.hideCompleted?'Show completed tasks':'Hide completed tasks'"
                    >
                      {{ this.hideCompleted?'Show Compl':'Hide Compl' }}
                    </button>
                    <button
                      class="btn btn-outline-secondary"
                      @click="deleteCompletedTasks"
                      type="button"
                      v-tooltip.top-center="'Delete completed task'"
                    >
                      Del Compl
                    </button>
                  </div>
                </div>
            </div>
          </card>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'

export default {
  name: 'MainPage',

  components: {
    InfiniteLoading
  },

  data () {
    return {
      hideCompleted: true,
      editOffset: -1,
      editTask: {},
      editTaskOri: {},
      editTooltip: 'Edit Task',
      deleteTooltip: 'Remove',
      page: 2
    }
  },

  computed: {
    allTasks () {
      return this.$store.getters.allTasks
    },

    pageTasks () {
      return this.$store.getters.pageTasks
    },

    doneTasks () {
      return this.$store.getters.doneTasks
    },

    filteredTasks () {
      return this.hideCompleted ? this.doneTasks : this.allTasks
    },

    orderedTasks () {
      return _.orderBy(this.filteredTasks, ['done', 'created_at'], ['asc', 'asc'])
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
        .then(() => {
          this.$nextTick(function () {
            if (this.$refs.tasksList) {
              this.$refs.tasksList.scrollTop = this.$refs.tasksList.scrollHeight
            }
          })
        })
    },

    addNewTask () {
      this.$store.dispatch('addTask', {
        'name': this.newTaskText
      })
        .then(() => {
          this.$nextTick(function () {
            this.$refs.tasksList.scrollTop = this.$refs.tasksList.scrollHeight
          })
        })
      this.$store.commit('updateNewTaskText', '')
    },

    hideComlTask () {
      this.hideCompleted = !this.hideCompleted
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
    },

    infiniteHandler ($state) {
      this.$store.dispatch('setPageTasks', this.page)
        .then(() => {
          if (this.pageTasks.length) {
            this.page += 1
            setTimeout(() => {
              $state.loaded()
            }, 1000)
          } else {
            $state.complete()
            if (this.$refs.tasksList) {
              this.$refs.tasksList.scrollTop = this.$refs.tasksList.scrollHeight
            }
          }
        })
    }
  }
}
</script>

<style scoped>
  .my-custom-scrollbar {
    position: relative;
    height: 55vh;
    overflow: auto;
  }
  .table-wrapper-scroll-y {
    display: block;
  }
</style>
