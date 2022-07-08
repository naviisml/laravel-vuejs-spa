<template>
	<div v-if="user">
		<!-- Big header -->
		<header class="hero hero-light">
			<div class="container py-5">
				<h2><i class="far fa-user"></i> {{ user.username }}</h2>
				<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
			</div>

            <nav class="nav-top nav-transparent">
                <div class="container">
                    <ul class="nav-list ml-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'admin.user.user-profile', params: { id: user.id } }">
                                <i class="far fa-user-cog"></i>
                                Profile
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'admin.user.user-logs', params: { id: user.id } }">
                                <i class="far fa-book-user"></i>
                                Logs
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'admin.user.user-roles', params: { id: user.id } }">
                                <i class="far fa-user-crown"></i>
                                Roles
                            </router-link>
                        </li>
                    </ul>
                </div>
            </nav>
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
