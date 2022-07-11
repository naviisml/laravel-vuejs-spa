<template>
	<div class="container py-5" v-if="user">
		<div class="card">
			<div class="card-content">
				<h3 class="pb-3">Change password</h3>

                <div v-if="form.hasMessage()" class="alert my-3" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>

				<form @submit.prevent="update">
					<!-- Old -->
					<div class="form-group py-3">
						<label>Old Password</label>
						<p v-if="form.hasError('old_password')" class="text-danger" v-html="form.hasError('old_password')"></p>
						<input class="form-control" v-model="form.old_password" type="password" name="old_password">
					</div>

					<!-- Password -->
					<div class="form-group py-3">
						<label>New Password</label>
						<p v-if="form.hasError('password')" class="text-danger" v-html="form.hasError('password')"></p>
						<input class="form-control" v-model="form.password" type="password" name="password">
					</div>

					<!-- Password Confirmation -->
					<div class="form-group py-3">
						<label>Confirm Password</label>
						<p v-if="form.hasError('password_confirmation')" class="text-danger" v-html="form.hasError('password_confirmation')"></p>
						<input class="form-control" v-model="form.password_confirmation" type="password" name="password_confirmation">
					</div>

					<!-- Submit Button -->
					<v-button :loading="form.busy">
						Update
					</v-button>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	import Form from '../../utils/vue-form'
	import { mapGetters } from 'vuex'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:user.edit-password'],

		computed: mapGetters({
			user: 'auth/user'
		}),

		data () {
			return {
				form: new Form({
					old_password: '',
					password: '',
					password_confirmation: ''
				})
			}
		},

		methods: {
			async update () {
				const { status } = await this.form.patch('/api/v1/me/password')

                if (status == 200) {
                    this.form.setMessage('Updated your account.')

				    this.$store.dispatch('auth/updateUser', { user: data })
                }
			}
		}
	}
</script>
