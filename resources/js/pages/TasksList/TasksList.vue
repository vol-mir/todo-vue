<template>
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
      id="tasks-list"
      class="my-custom-scrollbar table-wrapper-scroll-y"
      ref="tasksList"
      infinite-wrapper
    >
      <table class="table">
        <tbody>
        <infinite-loading
          @infinite="infiniteHandler"
          :distance="20"
          direction="top"
          ref="infiniteLoading">
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
              class="manage-task"
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
              class="manage-task"
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
      <div class="col-12 mb-3 input-group" v-if="this.windowWidth  > 768">
        <input
          type="text"
          v-model="newTaskText"
          class="form-control input-new-task"
          @keydown.enter="addNewTask"
          placeholder="New tasks..."
          @keydown.esc="cancelAddingTask"
          aria-label="New tasks..."
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
      <div class="col-12 mb-3" v-else>
        <div class="col-12 mb-3 input-group">
          <input
            type="text"
            v-model="newTaskText"
            class="form-control input-new-task"
            @keydown.enter="addNewTask"
            placeholder="New tasks..."
            @keydown.esc="cancelAddingTask"
            aria-label="New tasks..."
          >
          <div class="input-group-append">
            <button
              class="btn btn-outline-secondary btn-fill height-add-button"
              type="button"
              @click="addNewTask"
              v-tooltip.top-center="'Add new task'"
            >
              <i class="nc-icon nc-simple-add"></i>
            </button>
          </div>
        </div>
        <div class="col-12 mb-3">
          <a
            class="text-success"
            target="_blank"
            href="#" @click.prevent='hideComlTask'
          >
            {{ this.hideCompleted?'Show completed tasks':'Hide completed tasks' }}
          </a>
          <a
            class="text-danger"
            target="_blank"
            href="#" @click.prevent='deleteCompletedTasks'
          >
            Delete completed task
          </a>
        </div>
      </div>

    </div>
  </card>
</template>

<script>
import InfiniteLoading from '@components/InfiniteLoading/InfiniteLoading.vue'

export default {
  name: 'TasksList',

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
      page: 2,
      height: 0,
      windowWidth: window.innerWidth
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
      return this.allTasks.filter(t => !t.done)
    },

    filteredTasks () {
      return this.hideCompleted ? this.doneTasks : this.allTasks
    },

    orderedTasks () {
      return _.orderBy(this.filteredTasks, ['done', 'created_at'], ['asc', 'asc'])
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
    window.addEventListener('resize', this.setWindowWidth)

    this.fetchTasks()
  },

  methods: {
    setWindowWidth () {
      this.windowWidth = window.innerWidth
    },

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
      if (this.newTaskText.length === 0) {
        return
      }

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
      this.$nextTick(function () {
        this.$refs.tasksList.scrollTop = this.$refs.tasksList.scrollHeight
      })
    },

    cancelAddingTask () {
      this.$store.commit('updateNewTaskText', '')
    },

    deleteCompletedTasks () {
      this.$store.dispatch('deleteCompletedTasks')
        .then(() => {
          this.$nextTick(function () {
            if (this.$refs.tasksList) {
              this.page = 2
              const stateChanger = this.$refs.infiniteLoading.stateChanger
              stateChanger.reset()
              this.$refs.tasksList.scrollTop = this.$refs.tasksList.scrollHeight
            }
          })
        })
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
      if (this.editTask.name.length === 0) {
        return
      }
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

    infiniteHandler ($state) {
      this.$store.dispatch('setPageTasks', this.page)
        .then(() => {
          if (this.pageTasks.length) {
            this.page += 1
            setTimeout(() => {
              $state.loaded()
            }, 2000)
          } else {
            $state.complete()
          }
        })
    }
  }
}
</script>
