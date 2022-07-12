<template>
    <div class="container py-5" v-if="user">
        <div class="row">
            <div class="col-md-12 mx-2 mb-3">
                <div v-if="form.hasMessage()" class="alert" :class="{'alert-danger': form.status != 200, 'alert-success': form.status == 200}" v-html="form.hasMessage()"></div>
            </div>

            <div class="col-xs-12 col-md-6">
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
            <div class="col-xs-12 col-md-6">
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
                                    <button @click="this.delete(role.id)" class="btn btn-soft btn-danger tooltip-top" aria-label="Delete">
                                        <i class="far fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
				form: new Form(),
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
				const { status, data } = await this.form.get(`/api/v1/roles`, {})

                this.roles = data
			},
			async assign() {
				const { status, data } = await this.form.patch(`/api/v1/role/assign`, { user_id: this.user.id, role_id: this.role_id })

                if (status == 200) {
                    this.form.setMessage('Updated your account.')

				    this.updateUser(this.user.id)
                }
			},
			async delete(role_id) {
				const { status, data } = await this.form.delete(`/api/v1/role/delete`, { params: { user_id: this.user.id, role_id: role_id } })

                if (status == 200) {
                    this.form.setMessage('Updated your account.')

				    this.updateUser(this.user.id)
                }
			},
		},
	}
</script>
