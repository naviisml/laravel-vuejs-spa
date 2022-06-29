<template>
	<nav class="nav-top">
		<div class="container d-flex py-2">
			<h3 class="nav-branding pt-2">Branding</h3>

			<ul class="nav-list ml-auto">
				<li class="nav-item">
					<router-link class="nav-link" :to="{ name: 'home' }">
						Home
					</router-link>
				</li>
				<span v-if="user">
					<li class="nav-item">
						<router-link class="nav-link" :to="{ name: 'user.user-profile' }">
							Dashboard
						</router-link>
					</li>
					<li class="nav-item">
						<a class="nav-link" @click.prevent="logout">
							Sign Out
						</a>
					</li>
				</span>
				<span v-else>
					<li class="nav-item">
						<router-link class="nav-link" :to="{ name: 'login' }">
							Login
						</router-link>
					</li>
					<li class="nav-item">
						<router-link class="nav-link" :to="{ name: 'register' }">
							Register
						</router-link>
					</li>
				</span>
			</ul>
		</div>
	</nav>
</template>

<script>
	import { mapGetters } from 'vuex'

	export default {
		name: 'Navigation',

		computed: mapGetters({
			user: 'auth/user'
		}),

		methods: {
			async logout () {
				// Log out the user
				await this.$store.dispatch('auth/logout')

				// Redirect to home
				this.$router.push({ name: 'home' })
			}
		}
	}
</script>
