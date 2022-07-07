<template>
	<div class="row p-5" v-if="user">
		<div class="col-md-12 mx-2 mb-3">
			<div v-if="form.data && form.data.message" class="alert" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}">{{ form.data.message }}</div>
		</div>

		<div class="col-md-6">
			<div class="card mx-2">
				<div class="card-content">
					Assign new roles

					<form @submit.prevent="assignRole">
						<!-- Role -->
						<div class="form-group py-3" v-if="roles">
							<label>Role</label>
							<select class="form-control" name="primary_role" v-model="role_id">
								<option v-for="(role, key) in roles" :value="role.id">{{ role.displayname }}</option>
							</select>
							<p class="text-muted">Select the role you wanna assign to {{ user.realname }}</p>
						</div>

						<!-- Submit Button -->
						<v-button class="btn-block my-3" :loading="busy" @click="this.assign">
							Assign
						</v-button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card mx-2">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>Role</th>
							<th>Tag</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(role, key) in user.roles">
							<td>{{ role.data.displayname }}</td>
							<td>{{ role.data.tag }}</td>
							<td>
								<button v-if="!role.data.default" @click="this.delete(role.id)" class="btn btn-soft btn-danger tooltip-top" aria-label="Delete">
									<i class="far fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	import Form from '../../../utils/vue-form'

	export default {
		guards: 'permissions:admin.user.roles',

		props: {
			user: {
				type: Object,
				default: null
			},
			updateUser: {
				type: Function
			}
		},

		data() {
			return {
				form: {},
				role_id: null,
				roles: null,
				busy: false
			}
		},

		mounted () {
			this.fetch();
		},

		methods: {
			async fetch() {
				const { data } = await new Form({}).get(`/api/v1/roles`)

				this.roles = data
			},
			async assign() {
				this.form = new Form({ user_id: this.user.id, role_id: this.role_id })
				const { data } = await this.form.patch(`/api/v1/role/assign`)

				this.updateUser(this.user.id)
			},
			async delete(role_id) {
				this.form = new Form({				 params: { user_id: this.user.id, role_id: role_id } })
				const { data } = await this.form.delete(`/api/v1/role/delete`)

				this.updateUser(this.user.id)
			},
		},
	}
</script>
