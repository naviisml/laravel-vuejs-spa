<template>
	<div class="page" v-if="user">
		<!-- Big header -->
		<header class="page-header">
			<div class="px-5 pt-5 pb-3">
				<h2><i class="far fa-user"></i> Edit {{ user.firstname }}</h2>
				<p class="text-muted">Manage the users.</p>
			</div>

			<ul class="list-items">
				<li class="list-item">
					<router-link class="list-link" :to="{ name: 'admin.user.user-profile', params: { id: user.id } }">
						<i class="far fa-user-cog"></i>
						Profile
					</router-link>
				</li>
				<li class="list-item">
					<router-link class="list-link" :to="{ name: 'admin.user.user-logs', params: { id: user.id } }">
						<i class="far fa-book-user"></i>
						Logs
					</router-link>
				</li>
				<li class="list-item">
					<router-link class="list-link" :to="{ name: 'admin.user.user-roles', params: { id: user.id } }">
						<i class="far fa-user-crown"></i>
						Roles
					</router-link>
				</li>
			</ul>
		</header>

		<!-- Header with top navigation -->
		<router-view :user.sync="user" :updateUser.sync="this.getUser" />
	</div>
</template>

<script>
	import axios from 'axios'

	export default {
		layout: 'Auth',
		guards: [
			'auth',
			'permissions:admin,admin.user.get'
		],

		data () {
			return {
				user: null
			}
		},

		mounted () {
			this.getUser(this.$route.params.id)
		},

		methods: {
			async getUser(id) {
				const { data } = await axios.get('/api/v1/user/' + id)

				this.user = data
			}
		}
	}
</script>
