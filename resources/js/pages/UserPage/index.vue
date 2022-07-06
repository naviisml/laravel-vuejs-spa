<template>
	<div class="container" v-if="user">
		Hello, {{ user.username }}!
	</div>
</template>

<script>
	import { mapGetters } from 'vuex'

	export default {
		name: 'Dashboard',
        layout: 'Auth',
		guards: [
			'auth'
		],

		computed: mapGetters({
			user: 'auth/user',
		}),
	}
</script>
