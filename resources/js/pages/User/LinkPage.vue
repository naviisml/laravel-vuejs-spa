<template>
	<div class="container py-5">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-content">
                        <login-with-steam :user="user" class="my-3" />
                        <login-with-discord :user="user" class="my-3" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex'
    import LoginWithSteam from '../../components/Auth/LoginWithSteam'
    import LoginWithDiscord from '../../components/Auth/LoginWithDiscord'

	export default {
		name: 'Link',
        layout: 'Auth',
		guards: [
			'auth'
		],

        components: {
            LoginWithSteam,
            LoginWithDiscord
        },

		computed: mapGetters({
			user: 'auth/user',
		})
	}
</script>
