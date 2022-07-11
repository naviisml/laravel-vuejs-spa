<template>
	<div class="container py-5" v-if="user">
		<div class="card">
			<div class="card-content">
				<h3 class="pb-3">Information</h3>

                <div v-if="form.hasMessage()" class="alert my-3" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>

				<form @submit.prevent="update">
					<!-- Email -->
					<div class="form-group py-3">
						<label>Email</label>
						<p v-if="form.hasError('email')" class="text-danger" v-html="form.hasError('email')"></p>
						<input class="form-control" v-model="form.email" type="email" name="email">
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
				})
			}
		},

		mounted() {
			this.reset()
		},

		methods: {
			async update () {
				const { status } = await this.form.patch(`/api/v1/user/${this.user.id}`)

                if (status == 200) {
                    this.form.setMessage('Updated your account.')

				    this.updateUser(this.user.id)
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
