<template>
	<div class="container py-5">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-content">
						<h2>Sign Up</h2>

						<p v-if="form.data && form.data.message" class="text-danger">{{ form.data.message }}</p>

						<form @submit.prevent="register">
							<!-- Username -->
							<div class="form-group py-2">
								<label><strong>Username</strong></label>
								<input class="form-control" v-model="form.username" type="text" name="username" placeholder="Username">
								<p v-if="form.hasError('username')" class="text-danger">{{ form.hasError('username').message }}</p>
							</div>

							<!-- Email -->
							<div class="form-group py-2">
								<label><strong>Email</strong></label>
								<input class="form-control" v-model="form.email" type="email" name="email" placeholder="example@email.com">
								<p v-if="form.hasError('email')" class="text-danger">{{ form.hasError('email').message }}</p>
							</div>

							<!-- Password -->
							<div class="form-group py-2">
								<label><strong>Password</strong></label>
								<input class="form-control" v-model="form.password" type="password" name="password">
								<p v-if="form.hasError('password')" class="text-danger">{{ form.hasError('password').message }}</p>
							</div>

							<!-- Password Confirmation -->
							<div class="form-group py-2">
								<label><strong>Confirm Password</strong></label>
								<input class="form-control" v-model="form.password_confirmation" type="password" name="password_confirmation">
								<p v-if="form.hasError('password_confirmation')" class="text-danger">{{ form.hasError('password_confirmation').message }}</p>
							</div>

							<!-- TOS -->
							<div class="form-group py-2">
								<input type="checkbox" id="tos" value="tos">
								<label for="tos" class="text-muted">I agree with the <span class="text-primary">Terms</span> and <span class="text-primary">Privacy</span> policy</label>
							</div>

							<button class="btn btn-primary btn-block" type="submit">Register</button>
						</form>
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
				const { data } = await this.form.post('/api/v1/register')

				// Log in the user.
				const { data: { token } } = await this.form.post('/api/v1/login')

				// Save the token.
				this.$store.dispatch('auth/saveToken', { token })

				// Update the user.
				await this.$store.dispatch('auth/updateUser', { user: data })

				// Redirect home.
				this.$router.push({ name: 'user.user-profile' })
			}
		}
	}
</script>
