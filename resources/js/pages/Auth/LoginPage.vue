<template>
	<div class="container py-5">
		<div class="row">
            <div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-content">
						<h2 class="py-2">Sign In</h2>

                        <div v-if="form.hasMessage()" class="alert my-3" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>

						<form @submit.prevent="attemptLogin">
							<div class="form-group my-3">
								<label>Email</label>
								<input class="form-control" type="email" v-model="form.email" placeholder="Email">
								<p v-if="form.hasError('email')" class="text-danger" v-html="form.hasError('email')"></p>
							</div>
							<div class="form-group my-3">
								<label>Password</label>
								<input class="form-control" type="password" v-model="form.password" placeholder="Password">
								<p v-if="form.hasError('password')" class="text-danger" v-html="form.hasError('password')"></p>
							</div>

							<button class="btn btn-primary btn-block my-3" type="submit">Sign In</button>
						</form>

                        <p class="text-center text-muted">OR</p>

                        <login-with-oauth driver="discord" :callback="loginCallback" class="my-3" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Form from '../../utils/vue-form'

	export default {
		name: 'Login',
		guards: [
			'guest'
		],

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
				const { status, data } = await this.form.post('/api/v1/login')

                // check request status
                if (status != 200) {
                    return false
                }

				// Save the token.
				this.$store.dispatch('auth/saveToken', {
					token: data.token
				})

				// Fetch the user.
				await this.$store.dispatch('auth/fetchUser')

				// Redirect to home
				this.$router.push({ name: 'user.user-profile' })
			},
            loginCallback() {
				this.$router.push({ name: 'user.user-profile' })
            }
		},
	}
</script>
