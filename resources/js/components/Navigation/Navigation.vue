<template>
	<nav class="nav-top nav-transparent">
		<div class="container d-flex py-2">
			<ul class="nav-list ml-auto">
				<span v-if="auth">
					<li class="nav-item">
						<router-link class="nav-link color-primary" :to="{ name: 'user.user-profile' }">
							Dashboard
						</router-link>
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
			auth: 'auth/check'
		})
	}
</script>
