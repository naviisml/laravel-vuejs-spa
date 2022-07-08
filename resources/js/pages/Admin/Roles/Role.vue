<template>
	<div class="page" v-if="role">
		<!-- Header -->
		<header class="page-header border-bottom p-5">
			<h2><i class="far fa-tag"></i> {{ data.displayname ?? 'New Role' }}</h2>
			<p class="text-muted"> {{ data.tag ?? '@new-role' }}</p>
		</header>

		<!-- Information -->
		<section class="page-content p-5">
			<div class="row">
				<div class="col-md-6">
					<div class="card mx-2">
						<div class="card-content">
							<h3>Role</h3>
							<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

							<form @submit.prevent="editRole">
								<!-- displayname -->
								<div class="form-group py-3">
									<label>Displayname</label>
									<input class="form-control" v-model="data.displayname" type="text" name="text">
									<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
								</div>

								<!-- tag -->
								<div class="form-group py-3">
									<label>Tag</label>
									<input class="form-control" v-model="data.tag" @keyup="parseRoleTag" type="text" name="text">
									<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
								</div>

								<!-- Submit Button -->
								<v-button class="my-3">
									Update
								</v-button>
							</form>
						</div>
					</div>
				</div>

				<!-- Permissions -->
				<div class="col-md-6">
					<div class="card mx-2">
						<div class="card-content border-bottom">
							<h3>Permissions</h3>
							<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

							<!-- Add permission -->
							<form class="py-2" @submit.prevent="addPermission">
								<div class="row">
									<!-- Permission -->
									<div class="col-md-6">
										<label>Permissions</label>
										<input class="form-control p-2" v-model="permission.name" type="text" name="text">
									</div>

									<!-- Allowed -->
									<div class="col-md-3">
										<label>Allowed</label>
										<select class="form-control p-2" name="state" v-model="permission.state">
											<option value="1">Yes</option>
											<option value="0">No</option>
										</select>
									</div>

									<!-- Submit -->
									<div class="col-md-3">
										<label class="text-light">Add</label>
										<v-button class="btn-block p-2">
											<i class="fal fa-plus"></i>
											Add
										</v-button>
									</div>
								</div>
							</form>
						</div>

						<!-- Permissions -->
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>Permission</th>
									<th>Allowed</th>
									<th colspan="1"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(key, value) in role.permissions" :key="key">
									<td>{{ value }}</td>
									<td>
										<select class="form-control p-2" name="state">
											<option value="1">Yes</option>
											<option value="0" :selected="key == false">No</option>
										</select>
									</td>
									<td>
										<button class="btn btn-soft btn-danger tooltip-top" aria-label="Delete" @click="test">
											<i class="far fa-trash"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from 'axios'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles,admin.role.edit'],

		data () {
			return {
				data: {
					displayname: '',
					tag: '',
				},
				permission: {
					name: '',
					state: '1',
				},
				role: {
                    tag: '@',
                    permissions: []
                }
			}
		},

		created () {
            this.fetchRole(this.$route.params.id)
		},

		methods: {
            /**
             * Parse the role tag
             *
             * @return  {null}
             */
            parseRoleTag() {
                var tag = this.data.tag

                // remove all special characters from tag
                tag = tag.replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '')

                // replace all spaces with `-`
                tag = tag.replace(/\s+/g, '-')

                // make tag lowercase
                tag = tag.toLowerCase()

                // replace multiple dashes to one
                tag = tag.replace(/-+/g, "-")

                // replace form data
                this.data.tag = '@' + tag
            },
            /**
             * Fetch a role by id
             *
             * @param   {number}  id
             *
             * @return  {null}
             */
			async fetchRole(id) {
                if (!id) {
                    this.resetForm()
                    return false
                }

				const { data } = await axios.get(`/api/v1/role/${id}`)

				this.role = data
				this.resetForm()
			},
			editRole() {
				console.log('test')
			},
			test() {
				console.log(this.data)
			},
			addPermission() {
                const name = this.permission.name
                const state = this.permission.state == '1' ? true : false

                console.log(name, state)
			},
            /**
             * Reset the form with the new data
             *
             * @return  {null}
             */
			resetForm() {
				// Fill the form with user data.
				Object.keys(this.data).forEach(key => {
					this.data[key] = this.role[key]
				})
			}
		},

		watch: {
			"$route.params.id": function (id) {
				this.role = {
                    tag: '@',
                    permissions: []
                }
				this.fetchRole(id)
			}
		}
	}
</script>
