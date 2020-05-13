Vue.component('todo-app', {
	template: `
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
	`,
	data() {
		return {
			tasks: []
		}
	},
	methods: {
		getAllTasks() {
			axios.get('./app/get-tasks.php')
	        .then(response => this.tasks = response.data)
		}
	},
	created() {
		this.getAllTasks()
	}
})



Vue.component('add-task', {
	template: `
		<form v-on:submit.prevent="submitForm">
			<div class="input-field col s12">
				<input placeholder="What needs to be done?" v-model="task"/>
			</div>
		</form>`,

    data() {
		return {
			task: ''
		}
	},

	methods: {
		submitForm() {
			if(this.task) {
				var params = new URLSearchParams();
				params.append('name', this.task);

				axios.post('./app/store.php', params)
				  .then((response) => {
				  	this.$emit('update')
				  	this.task = ''
				  });
			}
		}
	}
})



Vue.component('tasks-list', {
	template: `
		<ul class="collection" v-show="tasks.length > 0">
		    <li class="collection-item left-align task-list" v-for="task in filteredTasks" :key="task.id" @dblclick="toggleEdit(task)">
		        <label v-if="task.editable != 1">
		            <input type="checkbox" class="filled-in" v-model="task.is_completed" true-value="1" false-value="0" />
		            <span :class="listClass(task)">{{ task.name }}</span>
		            <span class="right options" @click="deleteTask(task)">x</span>
		        </label>
		        <label v-else>
		        	<form v-on:submit.prevent="updateTaskName(task)">
						<div class="input-field">
							<input type="text" v-model="task.name"/>
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
		                <button class="button2" @click="currentFilter = 'active'">active</button>
		                <button class="button2" @click="currentFilter = 'completed'">completed</button>
		            </div>
		            <div class="col s3 mt-4">
		            	<a href="javascript:void(0)" @click="clearCompleted">Clear Completed</a>
	            	</div>
		        </div>
		    </li>
		</ul>
		`,

    props: ['tasks'],
    data() {
    	return {
    		currentFilter: 'all',
    		editOn: false
    	}
    },
    methods: {
    	toggleEdit(task) {
    		task.editable = !parseInt(task.editable)
    	},
    	updateTaskName(task) {
    		var params = new URLSearchParams();
			params.append('id', task.id);
			params.append('name', task.name);

			axios.post('./app/update.php', params)
			  .then((response) => {task.editable = 0});
    	},
    	deleteTask(task) {
    		var params = new URLSearchParams();
			params.append('id', task.id);

			axios.post('./app/single-delete.php', params)
			  .then((response) => {this.$emit('update')});
    	},
    	clearCompleted() {
    		var completedTasks;
    		completedTasks = this.filteredTasks.filter(task => {
    			return parseInt(task.is_completed)
    		})

    		var taskIds = completedTasks.map(a => a.id);
    		var params = new URLSearchParams();
			params.append('ids', taskIds);

    		axios.post('./app/delete.php', params)
			  .then((response) => {this.$emit('update')});
    	},
    	listClass(task) {
    		return parseInt(task.is_completed) ? "strikeout" : "plain"
    	}
    },
    computed: {
    	filteredTasks() {
    		if(this.currentFilter == 'all') {
    			return this.tasks
    		} else if(this.currentFilter == 'active') {
    			return this.tasks.filter(task => {
	    			return !parseInt(task.is_completed)
	    		})
    		} else {
    			return this.tasks.filter(task => {
	    			return parseInt(task.is_completed)
	    		})
    		}
    	},
    	remainingTasks() {
    		return this.tasks.filter(task => {
    			return !parseInt(task.is_completed)
    		})
    	}
    },	
    filters: {
    	pluralize(count) {
    		return (count == 1) ? 'item' : 'items'
    	}
    }
})

var app = new Vue({
	el: '#app',
})
