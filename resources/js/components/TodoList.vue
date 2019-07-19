<template>
  <div>
    <h4 class="bg-primary text-white text-center p-2">
      My's To Do List
    </h4>
    <div class="container-fluid p-4">
      <div class="row" v-if="filteredTasks.length == 0">
        <div class="col text-center">
          <b>Nothing to do. Hurrah!</b>
        </div>
      </div>
      <template v-else>
        <div class="row">
          <div class="col font-weight-bold">Task</div>
          <div class="col-2 font-weight-bold">Done</div>
        </div>
        <div class="row" v-for="task in filteredTasks" v-bind:key="task.id">
          <div v-show="editOffset != task.id" class="col">
            <span>{{task.name}}</span>
            <button type="button" v-on:click="startEditingTask(task.id)" class="btn btn-link">Edit</button>
            <button type="button" v-on:click="deleteTask(task)" class="btn btn-link">Delete</button>
          </div>
          <div v-show="editOffset==task.id" class="col">
            <input type="text"
              :id = "'item-task-'+task.id"
              @keydown.enter="updateTask"
              @keydown.esc="cancelEditingTask"
              class="form-control"
              v-model="editTask.name"/>
            <button type="button" v-on:click="updateTask()" class="btn btn-link">Update</button>
            <button type="button" v-on:click="cancelEditingTask()" class="btn btn-link">Cancel</button>
          </div>
          <div class="col-2 text-center">
            <input type="checkbox"
              v-bind:value=task.id
              v-bind:id="task.id"
              v-bind:checked="task.done"
              v-on:click="checkTask"
              class="form-check-input"/>
          </div>
        </div>
      </template>
      <div class="row py-2">
        <div class="col">
          <input type="text"
            v-model="newTaskText"
            class="form-control"
            @keydown.enter="addNewTask"
            @keydown.esc="cancelAddingTask"/>
        </div>
        <div class="col-2">
          <button class="btn btn-primary" v-on:click="addNewTask">Add</button>
        </div>
      </div>
      <div class="row bg-secondary py-2 mt-2 text-white">
        <div class="col text-center">
          <input type="checkbox"
             v-model="hideCompleted"
             class="form-check-input"/>
          <label class="form-check-label font-weight-bold">
            Hide completed tasks
          </label>
        </div>
        <div class="col text-center">
          <button class="btn btn-sm btn-warning" v-on:click="deleteCompletedTasks">Delete Completed</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TodoList',
  data () {
    return {
      hideCompleted: true,
      newTaskText: '',
      editOffset: -1,
      editTask: {},
      editTaskOri: {}
    }
  },

  computed: {
    allTasks () {
      return this.$store.getters.GET_TASKS
    },

    actualTasks () {
      return this.$store.getters.ACTUAL_TASKS
    },

    filteredTasks () {
      return this.hideCompleted ? this.actualTasks : this.allTasks
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
      this.newTaskText = ''
    },

    cancelAddingTask () {
      this.newTaskText = ''
    },

    deleteCompletedTasks () {
      this.$store.dispatch('deleteCompletedTasks')
    },

    deleteTask (task) {
      this.$store.dispatch('deleteTask', task)
    },

    startEditingTask (idTask) {
      this.editOffset = idTask
      const findTask = this.filteredTasks.find(elem => elem.id === idTask)
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
      this.$set(this.filteredTasks, this.editOffset, this.editTaskOri)
      this.editOffset = -1
      this.editTaskOri = {}
      this.editTask = {}
    },

    checkTask (e) {
      this.$store.dispatch('checkTask', {
        'done': e.target.checked,
        'id': e.target.value
      })
    }

  }
}
</script>

<style scoped>

</style>
