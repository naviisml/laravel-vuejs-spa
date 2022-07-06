<template>
	<div class="container" v-if="user">
		Hello, {{ user.username }}!

        <login-with-steam />
	</div>
</template>

<script>
	import { mapGetters } from 'vuex'
    import LoginWithSteam from '../../components/Auth/LoginWithSteam'

	export default {
		name: 'Dashboard',
        layout: 'Auth',
		guards: 'auth',

        components: {
            LoginWithSteam,
        },

		computed: mapGetters({
			user: 'auth/user',
		}),
	}
</script>
