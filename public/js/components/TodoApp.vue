<template>
  <div class="card-content valign center">
    <h1 class="red-text text-lighten-4">todos</h1>

    <div class="container">
      <div class="col s6 offset-s3">
        <div class="row">
          <div class="col s8 offset-s2">
            <add-task @update="getAllTasks"></add-task>
            <tasks-list :tasks="tasks" @update="getAllTasks"></tasks-list>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import AddTask from './AddTask.vue';
import TasksList from './TasksList.vue';

export default {
  components: {
    AddTask,
    TasksList,
  },
  data() {
    return {
      tasks: [],
    };
  },
  methods: {
    getAllTasks() {
      axios.get('./app/get-tasks.php').then((response) => (this.tasks = response.data));
    },
  },
  created() {
    this.getAllTasks();
  },
};
</script>
