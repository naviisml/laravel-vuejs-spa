<template>
	<div class="page" v-if="users">
		<!-- Header -->
		<header class="page-header border-bottom p-5">
			<h2><i class="far fa-users"></i> Users</h2>
			<p class="text-muted">Manage the users.</p>
		</header>

		<!-- Users -->
		<section class="page-content p-5">
			<div class="card">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th colspan="1">Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(user, key) in users.data">
							<th>{{ user.id }}</th>
							<td>{{ user.username }}</td>
							<td>{{ user.email }}</td>
							<td>
								<router-link :to="{ name: 'admin.user.user-profile', params: { id: user.id } }" class="btn btn-soft tooltip-top" aria-label="Edit User">
									<i class="far fa-user-cog"></i>
								</router-link>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- Table Controls -->
				<div class="card-content">
					<div class="d-flex">
						<p class="text-muted">Page {{ page }} / {{ users.last_page }}</p>

						<div class="ml-auto">
							<button class="btn btn-soft" @click="previousPage">
								<i class="far fa-long-arrow-left"></i>
							</button>
							<button class="btn btn-soft" @click="nextPage">
								<i class="far fa-long-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from 'axios'

	export default {
		layout: 'Auth',
		guards: [
			'auth',
			'permissions:admin.users'
		],

		data () {
			return {
				page: 1,
				users: null
			}
		},

		mounted() {
			this.getUsers(this.page)
		},

		methods: {
			async getUsers (page) {
				const { data } = await axios.get('/api/v1/users?page=' + page)

				this.users = data
				this.page = page
			},
			previousPage() {
				this.setPage(this.page - 1)
			},
			nextPage() {
				this.setPage(this.page + 1)
			},
			setPage(page) {
				if (page <= 0 || page > this.users.last_page) {
					return false, console.error("Page doesn't exist." + page)
				}
				this.getUsers(page)
			},
		}
	}
</script>
