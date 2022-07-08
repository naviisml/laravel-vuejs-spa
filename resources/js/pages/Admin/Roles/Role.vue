<template>
    <transition name="animation-fade" mode="out-in" appear>
        <section v-if="role" class="container py-4">
            <form @submit.prevent="editRole">
                <!-- Header -->
                <div class="">
                    <h2><i class="far fa-tag"></i> {{ form.displayname ?? 'New Role' }}</h2>
                    <p class="text-muted"> {{ form.tag ?? '@new-role' }}</p>
                </div>

                <!-- Information -->
                <div class="card my-3">
                    <div class="card-content">
                        <h3>Role</h3>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                        <!-- displayname -->
                        <div class="form-group py-3">
                            <label>Displayname</label>
                            <input class="form-control" v-model="form.displayname" type="text" name="text">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        </div>

                        <!-- tag -->
                        <div class="form-group py-3">
                            <label>Tag</label>
                            <input class="form-control" v-model="form.tag" @keyup="parseRoleTag" type="text" name="text">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="card my-3">
                    <div class="card-content">
                        <h3>Permissions</h3>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
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
                            <tr v-for="(value, key) in permissions" :key="key">
                                <td>{{ $t(`permissions.${value}.title`) }} <i class="text-muted">{{ $t(`permissions.${value}.description`) }}</i></td>
                                <td>
                                    <select class="form-control p-2" name="state">
                                        <option value="1">Yes</option>
                                        <option value="0" :selected="!role.permissions || (role.permissions[value] ?? false) == false">No</option>
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

                <!-- Submit Button -->
                <v-button class="my-3">
                    Update
                </v-button>
            </form>
        </section>
    </transition>
</template>

<script>
	import axios from 'axios'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles,admin.role.edit'],

		data () {
			return {
				form: {
                    displayname: '',
                    tag: '',
                    permissions: []
                },
				role: null,
				permissions: [
                    'interact',
                    'test'
                ]
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
                var tag = this.form.tag

                // remove all special characters from tag
                tag = tag.replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '')

                // replace all spaces with `-`
                tag = tag.replace(/\s+/g, '-')

                // make tag lowercase
                tag = tag.toLowerCase()

                // replace multiple dashes to one
                tag = tag.replace(/-+/g, "-")

                // replace form data
                this.form.tag = '@' + tag
            },
            /**
             * Fetch a role by id
             *
             * @param   {number}  id
             *
             * @return  {null}
             */
			async fetchRole(id = false) {
                // reset the form to default data
                if (!id) {
                    this.role = {
                        displayname: 'New Role',
                        tag: '@new-role',
                        permissions: {}
                    }
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
			resetForm(data = null) {
                if (!data) {
                    data = this.role
                }

				// Fill the form with user data.
				Object.keys(this.form).forEach(key => {
					this.form[key] = data[key] ?? null
				})
			}
		},

		watch: {
			"$route.params.id": function (id) {
                this.role = null

                this.$nextTick(() => this.fetchRole(id))
			}
		}
	}
</script>

<style scoped>
.card {
    border: none;
    box-shadow: none;
    border-radius: 15px;
}
</style>
