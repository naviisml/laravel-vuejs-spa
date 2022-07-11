<template>
	<div class="row row-stretch">
		<div class="col-xs-12 col-md-3 p-4">
            <nav class="nav-left p-3">
                <!-- Security -->
                <strong class="title d-flex">
                    Roles
                    <div class="ml-auto">
                        <router-link :to="{ name: 'admin.role.create' }" class="tooltip-left" aria-label="Create new role">
                            <i class="far fa-plus"></i>
                        </router-link>
                    </div>
                </strong>
                <ul class="nav-list" v-if="roles">
                    <li class="nav-item pb-2" v-for="(role, key) in roles">
                        <router-link class="btn btn-soft btn-block" :to="{ name: 'admin.role.edit', params: { id: role.id } }" exact-active-class="btn-active">
                            <strong>{{ role.displayname }}</strong> <i>{{ role.tag }}</i>
                        </router-link>
                    </li>
                    <li class="nav-item pb-2" v-if="$route.name.match('admin.role.create*')">
                        <router-link class="btn btn-soft btn-block btn-active" :to="{ name: 'admin.role.create' }">
                            <strong>{{ form.displayname ?? 'New Role' }}</strong> <i>{{ form.tag ?? '@new-role' }}</i>
                        </router-link>
                    </li>
                </ul>
            </nav>
		</div>

		<div class="col-xs-12 col-md-9">
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
		</div>
	</div>
</template>

<script>
	import axios from 'axios'
	import { mapGetters } from 'vuex'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles,admin.role.edit'],

		data () {
			return {
				roles: null,
				role: null,
				form: {
                    displayname: '',
                    tag: '',
                    permissions: []
                },
				permissions: [
                    'interact',
                    'test'
                ]
			}
		},

		mounted () {
			this.fetchRoles()
            this.fetchRole(this.$route.params.id)
		},

		methods: {
            /**
             * Fetch the roles
             *
             * @return  {null}
             */
			async fetchRoles() {
				const { data } = await axios.get(`/api/v1/roles`)

				this.roles = data
			},
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

		computed: mapGetters({
			user: 'auth/user'
		}),

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
.nav-left {
    box-shadow: none;
    border: none;
	height: 100%;
    width: 100%;
    overflow: hidden;
    border-radius: 15px;
}

.nav-left .nav-link {
	padding: 5px 15px !important;
}
.title {
	text-transform: uppercase;
	color: rgb(var(--color-muted));
	display: block;
	padding: 15px;
	font-size: 14px;
}
.row-stretch
{
    grid-gap: 0;
    display: grid;
    grid-template-columns: repeat(12, calc(100% / 12));
	position: relative;
	height: 100%;
}
</style>
