<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <tasks-list></tasks-list>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TasksList from '@pages/TasksList/TasksList.vue'

export default {
  name: 'MainPage',

  components: {
    TasksList
  }
}
</script>
