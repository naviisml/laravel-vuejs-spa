<template>
	<div class="container py-5" v-if="user">
		<div class="card">
			<div class="card-content">
				<h3 class="pb-3">Information</h3>

                <div v-if="form.hasMessage()" class="alert my-3" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>

				<form @submit.prevent="update">
					<!-- Username -->
					<div class="form-group py-3">
						<label>Username</label>
						<input class="form-control" type="text" name="username" :value="user.username" disabled>
						<p v-if="form.hasError('username')" class="text-danger" v-html="form.hasError('username')"></p>
                        <p v-else class="text-muted">You cannot change your username (yet).</p>
					</div>

					<!-- Email -->
					<div class="form-group py-3">
						<label>Email</label>
						<input class="form-control" v-model="form.email" type="email" name="email">
						<p v-if="form.hasError('email')" class="text-danger" v-html="form.hasError('email')"></p>
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
		guards: ['auth', 'permissions:user.edit-profile'],

		computed: mapGetters({
			user: 'auth/user'
		}),

		data () {
			return {
				form: new Form({
					email: '',
				})
			}
		},

		mounted() {
			this.reset()
		},

		methods: {
			async update () {
				const { data, status } = await this.form.patch('/api/v1/me/profile')

                if (status == 200) {
                    this.form.setMessage('Updated your account.')

				    this.$store.dispatch('auth/updateUser', { user: data })
                }
			},
			reset() {
				// Fill the form with user data.
				Object.keys(this.form).forEach(key => {
					this.form[key] = this.user[key]
				})
			}
		}
	}
</script>
