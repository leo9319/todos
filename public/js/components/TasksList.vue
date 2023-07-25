<template>
  <ul class="collection" v-show="tasks.length > 0">
    <li class="collection-item left-align task-list" v-for="task in filteredTasks" :key="task.id" @dblclick="toggleEdit(task)">
      <label v-if="task.editable !== 1">
        <input type="checkbox" class="filled-in" v-model="task.is_completed" true-value="1" false-value="0" />
        <span :class="listClass(task)">{{ task.name }}</span>
        <span class="right options" @click="deleteTask(task)">x</span>
      </label>
      <label v-else>
        <form @submit.prevent="updateTaskName(task)">
          <div class="input-field">
            <input type="text" v-model="task.name" />
          </div>
        </form>
      </label>
    </li>
    <li class="collection-item left-align">
      <div class="row">
        <div class="col s4">
          <p>{{ remainingTasks.length }} {{ remainingTasks.length | pluralize }} left</p>
        </div>
        <div class="col s5 mt-4">
          <button class="button2" @click="currentFilter = 'all'">All</button>
          <button class="button2" @click="currentFilter = 'active'">Active</button>
          <button class="button2" @click="currentFilter = 'completed'">Completed</button>
        </div>
        <div class="col s3 mt-4">
          <a href="javascript:void(0)" @click="clearCompleted">Clear Completed</a>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
import axios from 'axios';

export default {
  props: ['tasks'],
  data() {
    return {
      currentFilter: 'all',
      editOn: false,
    };
  },
  methods: {
    toggleEdit(task) {
      task.editable = !parseInt(task.editable);
    },
    updateTaskName(task) {
      const params = new URLSearchParams();
      params.append('id', task.id);
      params.append('name', task.name);

      axios.post('./app/update.php', params).then((response) => {
        task.editable = 0;
      });
    },
    deleteTask(task) {
      const params = new URLSearchParams();
      params.append('id', task.id);

      axios.post('./app/single-delete.php', params).then((response) => {
        this.$emit('update');
      });
    },
    clearCompleted() {
      const completedTasks = this.filteredTasks.filter((task) => parseInt(task.is_completed));
      const taskIds = completedTasks.map((a) => a.id);
      const params = new URLSearchParams();
      params.append('ids', taskIds);

      axios.post('./app/delete.php', params).then((response) => {
        this.$emit('update');
      });
    },
    listClass(task) {
      return parseInt(task.is_completed) ? 'strikeout' : 'plain';
    },
  },
  computed: {
    filteredTasks() {
      if (this.currentFilter === 'all') {
        return this.tasks;
      } else if (this.currentFilter === 'active') {
        return this.tasks.filter((task) => !parseInt(task.is_completed));
      } else {
        return this.tasks.filter((task) => parseInt(task.is_completed));
      }
    },
    remainingTasks() {
      return this.tasks.filter((task) => !parseInt(task.is_completed));
    },
  },
  filters: {
    pluralize(count) {
      return count === 1 ? 'item' : 'items';
    },
  },
};
</script>
