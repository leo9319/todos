Vue.component('nav-bar', {
	template: `
		<nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700 relative z-10">
		  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
			<a href="/index.php" class="flex items-center">
				<img src="../images/logo.png" class="h-8 mr-3" alt="App Logo" />
				<span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Todo App</span>
			</a>
			<button data-collapse-toggle="navbar-dropdown" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-dropdown" aria-expanded="false">
				<span class="sr-only">Open main menu</span>
				<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
					<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
				</svg>
			</button>
			<div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
			  <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
				<li>
				  <a href="/index.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Todos</a>
				</li>
				<li>
				  <a href="/categories.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Categories</a>
				</li>
				<li v-if="is_premium != 1">
				  <a href="/purchase.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Get Premium</a>
				</li>
				<li>
					<button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">{{ username }} <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
						<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
					  </svg>
				    </button>
					<!-- Dropdown menu -->
					<div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
						<div class="py-1">
						  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white" @click.prevent="logout">Sign out</a>
						</div>
					</div>
				</li>
			  </ul>
			</div>
		  </div>
		</nav>
	`,
	data() {
		return {
			user: [],
			is_premium: 0,
			username: 'user',
		}
	},
	methods: {
		getUser() {
			axios.get('./app/get-user.php')
				.then(response => {
					this.user = response.data
					this.is_premium = this.user[0].is_premium
					this.username = this.user[0].username
				})
		},
		logout() {
			axios.get('./../login.php?logout=true')
				.then(response => {
					console.log('logged out')
					window.location.href = './../login.php';
				})
		}
	},
	created() {
		this.getUser()
	}
})

Vue.component('todo-app', {
	template: `
		<div class="bg-gray-100">
			<div class="min-h-screen flex items-center justify-center -mt-10">
				<div class="bg-white rounded-lg shadow-lg p-8 w-8/12">
					<h1 class="text-red-500 font-bold mb-4 text-5xl text-center opacity-50">todos</h1>
					<div class="container">
						<div class="flex flex-col space-y-4">
							<div class="w-3/4 mx-auto">
								<add-task @update="getAllTasks" :categories="categories"></add-task>
							</div>
			
							<div class="w-3/4 mx-auto">
								<tasks-list :tasks="tasks" :categories="categories" @update="getAllTasks"></tasks-list>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	`,
	data() {
		return {
			tasks: [],
			categories: [],
		}
	},
	methods: {
		getAllTasks() {
			axios.get('./app/get-tasks.php')
				.then(response => this.tasks = response.data)
		},
		getCategories() {
			axios.get('./app/get-categories.php')
				.then(response => {
					this.categories = response.data
				})
		}
	},
	created() {
		this.getAllTasks();
		this.getCategories();
	}
})

Vue.component('add-task', {
	template: `
		<form @submit.prevent="submitForm" class="mt-4">
			<div class="w-full flex space-x-2 form-row">
				<div class="w-3/4">
					<input
						type="text"
						placeholder="What needs to be done?"
						v-model="task"
						class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
					/>
				</div>
				<div class="w-1/4">
					<select v-model="cat_id" name="cat_id" id="" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
						<option value="0">General</option>
						<option :value="category.id" v-for="category in categories">{{ category.name }}</option>
					</select>
				</div>
			</div>
			<p v-if="errorMessage !== ''" class="text-xs text-red-500 mt-1">{{ errorMessage }}</p>
		</form>`,

	props: ['categories'],
	data() {
		return {
			task: '',
			cat_id: 0,
			errorMessage: ''
		}
	},

	methods: {
		submitForm() {
			if(this.task) {
				this.errorMessage = ''
				let params = new URLSearchParams();
				params.append('name', this.task);
				params.append('cat_id', this.cat_id);

				axios.post('./app/store.php', params)
					.then((response) => {
						this.$emit('update')
						this.task = ''
					}).catch(error => {
						if(error.status === 400) {
							this.errorMessage = 'Limit crossed! Please purchase the full version!'
						}
				});
			}
		},
	},
})

Vue.component('tasks-list', {
	template: `
		<div>
			<h3 class="my-2">Filter By Categories</h3>
			<div class="space-x-1">
				<button class="hover:bg-green-900 text-green-700 font-semibold hover:text-white px-2 border border-green-900 hover:border-transparent rounded text-xs" @click="setCategoryId('-1')">All Categories</button>
				<button class="hover:bg-green-900 text-green-700 font-semibold hover:text-white px-2 border border-green-900 hover:border-transparent rounded text-xs" @click="setCategoryId('0')">General</button>
				<button v-for="category in categories" :key="category.id" class="hover:bg-green-900 text-green-700 font-semibold hover:text-white px-2 border border-green-900 hover:border-transparent rounded text-xs" @click="setCategoryId(category.id)">{{ category.name }}</button>
			</div>
			<div class="h-72 overflow-scroll">
				<ul class="" v-show="tasks.length > 0">
					<a href="javascript:void(0)" @click="clearCompleted" class="mt-4 text-red-500 text-xs flex justify-end items-center space-x-4">
						<i class="fa-solid fa-xmark pr-1"></i>
						Clear Completed
					</a>
					<li class="py-1" v-for="task in filteredTasks" :key="task.id" @dblclick="toggleEdit(task)">
						<div class="flex items-center justify-between border border-gray-100 p-2 rounded-lg shadow-sm" v-if="task.editable != 1">
							<label >
								<input type="checkbox" class="" v-model="task.is_completed" true-value="1" false-value="0" />
								<span :class="listClass(task)">{{ task.name }}</span>
							</label>
							<span class="text-red-500 text-sm cursor-pointer" @click="deleteTask(task)"><i class="fa-solid fa-trash-can"></i></span>
						</div>
						<label v-else>
							<form v-on:submit.prevent="updateTaskName(task)">
								<div class="">
									<input type="text" v-model="task.name"/>
								</div>
							</form>
						</label>
					</li>
					<li class="">
						<div class="">
							<div class="text-right">
								<p class="text-xs">{{ remainingTasks.length }} {{ remainingTasks.length | pluralize }} left</p>
							</div>
							<div class="flex flex-row space-x-2">
								<button class="bg-transparent hover:bg-blue-900 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-900 hover:border-transparent rounded text-xs" @click="currentFilter = 'all'">All</button>
								<button class="bg-transparent hover:bg-blue-900 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-900 hover:border-transparent rounded text-xs" @click="currentFilter = 'active'">active</button>
								<button class="bg-transparent hover:bg-blue-900 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-900 hover:border-transparent rounded text-xs" @click="currentFilter = 'completed'">completed</button>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		`,

	props: ['tasks', 'categories'],
	data() {
		return {
			currentFilter: 'all',
			editOn: false,
			cat_id: -1
		}
	},
	methods: {
		setCategoryId(id) {
			this.cat_id = id;
			console.log(this.cat_id)
		},
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

			let filtered = [];
			if(this.currentFilter == 'all') {
				filtered = this.tasks
			} else if(this.currentFilter == 'active') {
				filtered = this.tasks.filter(task => {
					return !parseInt(task.is_completed)
				})
			} else {
				filtered = this.tasks.filter(task => {
					return parseInt(task.is_completed)
				})
			}

			if(this.cat_id != -1) {
				return filtered.filter(task => {
					return task.cat_id == this.cat_id
				})
			} else {
				return filtered
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

Vue.component('categories', {
	template: `
		<div class="bg-gray-100">
			<div class="min-h-screen flex items-center justify-center -mt-32">
				<div class="bg-white rounded-lg shadow-lg p-8 w-8/12">
					<h1 class="text-blue-500 font-bold mb-4 text-5xl text-center opacity-50">categories</h1>
			
					<div class="container">
						<div class="flex flex-col space-y-4">
							<div class="w-3/4 mx-auto">
								<add-categories @update="getAllCategories"></add-categories>
							</div>
			
							<div class="w-3/4 mx-auto">
								<categories-list :categories="categories" @update="getAllCategories"></categories-list>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	`,
	data() {
		return {
			categories: []
		}
	},
	methods: {
		getAllCategories() {
			axios.get('./app/get-categories.php')
				.then(response => this.categories = response.data)
		}
	},
	created() {
		this.getAllCategories()
	}
})

Vue.component('add-categories', {
	template: `
		<form @submit.prevent="submitForm" class="mt-4">
			<div class="w-full">
				<input
					type="text"
					placeholder="Add Categories"
					v-model="category"
					class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
				/>
			</div>
		</form>`,

	data() {
		return {
			category: ''
		}
	},

	methods: {
		submitForm() {
			if(this.category) {
				var params = new URLSearchParams();
				params.append('name', this.category);

				axios.post('./app/store-category.php', params)
					.then((response) => {
						this.$emit('update')
						this.category = ''
					});
			}
		}
	}
})

Vue.component('categories-list', {
	template: `
		<ul class="">
		    <li class="py-1" v-for="category in categories" :key="category.id" @dblclick="toggleEdit(category)">
		        <div class="flex items-center justify-between border border-gray-100 p-2 rounded-lg shadow-sm" v-if="category.editable != 1">
					<label >
						<span>{{ category.name }}</span>
					</label>
					<span class="text-red-500 text-sm cursor-pointer" @click="deleteCategory(category)"><i class="fa-solid fa-trash-can"></i></span>
				</div>
		        <label v-else>
		        	<form v-on:submit.prevent="updateCategoryName(category)">
						<div class="">
							<input type="text" v-model="category.name"/>
						</div>
					</form>
		        </label>
		    </li>
		</ul>
		`,

	props: ['categories'],
	data() {
		return {
			editOn: false
		}
	},
	methods: {
		toggleEdit(category) {
			category.editable = !parseInt(category.editable)
		},
		updateCategoryName(category) {
			var params = new URLSearchParams();
			params.append('id', category.id);
			params.append('name', category.name);

			// axios.post('./app/update.php', params)
			// 	.then((response) => {category.editable = 0});
		},
		deleteCategory(category) {
			var params = new URLSearchParams();
			params.append('id', category.id);

			axios.post('./app/single-delete-category.php', params)
				.then((response) => {this.$emit('update')});
		}
	},
	computed: {
		filteredCategories() {
			return this.categories
		}
	}
})

var app = new Vue({
	el: '#app',
})
