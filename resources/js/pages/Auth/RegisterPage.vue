<template>
	<div class="container py-5">
		<div class="row">
            <div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-content">
						<h2>Sign Up</h2>

                        <div v-if="form.hasMessage()" class="alert my-3" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>

						<form @submit.prevent="register">
							<!-- Username -->
							<div class="form-group py-2">
								<label><strong>Username</strong></label>
								<input class="form-control" v-model="form.username" type="text" name="username" placeholder="Username">
								<p v-if="form.hasError('username')" class="text-danger" v-html="form.hasError('username')"></p>
							</div>

							<!-- Email -->
							<div class="form-group py-2">
								<label><strong>Email</strong></label>
								<input class="form-control" v-model="form.email" type="email" name="email" placeholder="example@email.com">
								<p v-if="form.hasError('email')" class="text-danger" v-html="form.hasError('email')"></p>
							</div>

							<!-- Password -->
							<div class="form-group py-2">
								<label><strong>Password</strong></label>
								<input class="form-control" v-model="form.password" type="password" name="password">
								<p v-if="form.hasError('password')" class="text-danger" v-html="form.hasError('password')"></p>
							</div>

							<!-- Password Confirmation -->
							<div class="form-group py-2">
								<label><strong>Confirm Password</strong></label>
								<input class="form-control" v-model="form.password_confirmation" type="password" name="password_confirmation">
								<p v-if="form.hasError('password_confirmation')" class="text-danger" v-html="form.hasError('password_confirmation')"></p>
							</div>

							<!-- TOS -->
							<div class="form-group pt-3 pb-2">
								<input type="checkbox" id="tos" value="tos">
								<label for="tos" class="text-muted">I agree with the <span class="text-primary">Terms</span> and <span class="text-primary">Privacy</span> policy</label>
							</div>

							<button class="btn btn-primary btn-block my-3" type="submit">Register</button>
						</form>

                        <p class="text-center text-muted">OR</p>

                        <login-with-oauth driver="steam" :callback="loginCallback" class="btn-block btn-outline my-3" />
                        <login-with-oauth driver="discord" :callback="loginCallback" class="btn-block btn-outline my-3" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Form from '../../utils/vue-form'

	export default {
		name: 'Register',
		guards: [
			'guest'
		],

		data () {
			return {
				form: new Form({
					username: '',
					email: '',
					password: '',
					password_confirmation: ''
				})
			}
		},

		methods: {
			async register () {
				// Register the user.
				const { status, data } = await this.form.post('/api/v1/register')

                // check request status
                if (status != 200) {
                    return false
                }

				// Log in the user.
				const { data: { token } } = await this.form.post('/api/v1/login')

				// Save the token.
				this.$store.dispatch('auth/saveToken', { token })

				// Update the user.
				await this.$store.dispatch('auth/updateUser', { user: data })

				// Redirect home.
				this.$router.push({ name: 'user.user-profile' })
			},
            loginCallback() {
				this.$router.push({ name: 'user.user-profile' })
            }
		}
	}
</script>
