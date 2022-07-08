<template>
	<div class="container py-5" v-if="user">
		<div class="card">
			<div class="card-content">
				<h3 class="pb-3">Information</h3>

				<p v-if="form.data && form.data.message" class="text-danger">{{ form.data.message }}</p>

				<form @submit.prevent="update">
					<!-- Email -->
					<div class="form-group py-3">
						<label>Email</label>
						<p v-if="form.hasError('email')" class="text-danger">{{ form.hasError('email').message }}</p>
						<input class="form-control" v-model="form.email" type="email" name="email">
						<p class="text-muted">The email accociated with your 42dashboard account</p>
					</div>

					<!-- Primary role -->
					<div class="form-group py-3">
						<label>Primary role</label>
						<p v-if="form.hasError('role')" class="text-danger">{{ form.hasError('role').message }}</p>
						<select class="form-control" name="primary_role" v-model="form.primary_role">
							<option v-for="(role, key) in user.roles" :value="role.role">{{ role.data.displayname }}</option>
						</select>
						<p class="text-muted">The primary role that will be displayed publicly.</p>
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
	import Form from '../../../utils/vue-form'

	export default {
		guards: 'permissions:admin.user.get',

		props: {
			user: {
				type: Object,
				default: null
			}
		},

		data () {
			return {
				form: new Form({
					email: '',
					primary_role: '',
				})
			}
		},

		mounted() {
			this.reset()
		},

		methods: {
			async update () {
				const { data } = await this.form.patch(`/api/v1/user/${this.user.id}`)

				this.updateUser(this.user.id)
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
