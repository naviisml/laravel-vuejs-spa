<template>
	<div class="container py-5">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-content">
						<h2 class="py-2">Sign In</h2>

						<p v-if="form.data && form.data.message" class="text-danger" v-html="form.data.message"></p>

						<form @submit.prevent="attemptLogin">
							<div class="form-group my-3">
								<label>Email</label>
								<input class="form-control" type="email" v-model="form.email" placeholder="Email">
								<p v-if="form.hasError('email')" class="text-danger">{{ form.hasError('email').message }}</p>
							</div>
							<div class="form-group my-3">
								<label>Password</label>
								<input class="form-control" type="password" v-model="form.password" placeholder="Password">
								<p v-if="form.hasError('password')" class="text-danger">{{ form.hasError('password').message }}</p>
							</div>

							<button class="btn btn-primary btn-block my-3" type="submit">Sign In</button>
						</form>

                        <login-with-steam class=" my-3" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Form from '../../utils/vue-form'
    import LoginWithSteam from '../../components/LoginWithSteam'

	export default {
		name: 'Login',
		guards: [
			'guest'
		],

        components: {
            LoginWithSteam
        },

		data() {
			return {
				form: new Form({
					email: '',
					password: ''
				})
			}
		},

		methods: {
			async attemptLogin() {
				const { data } = await this.form.post('/api/v1/login')

				// Save the token.
				this.$store.dispatch('auth/saveToken', {
					token: data.token
				})

				// Fetch the user.
				await this.$store.dispatch('auth/fetchUser')

				// Redirect to home
				this.$router.push({ name: 'user.user-profile' })
			}
		},
	}
</script>
