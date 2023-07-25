<template>
  <form @submit.prevent="submitForm">
    <div class="input-field col s12">
      <input placeholder="What needs to be done?" v-model="task" />
    </div>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      task: '',
    };
  },
  methods: {
    submitForm() {
      if (this.task) {
        const params = new URLSearchParams();
        params.append('name', this.task);

        axios.post('./app/store.php', params).then((response) => {
          this.$emit('update');
          this.task = '';
        });
      }
    },
  },
};
</script>
