<template>
	<div class="row row-stretch">
        <!-- Menu -->
		<div class="col-xs-12 col-md-3 p-4">
            <nav class="nav-left p-3">
                <strong class="title d-flex">
                    Roles
                    <div class="ml-auto">
                        <a class="tooltip-left" aria-label="Create new role" @click.prevent="createNewRole">
                            <i class="far fa-plus"></i>
                        </a>
                    </div>
                </strong>
                <ul class="nav-list" v-if="roles">
                    <li class="nav-item pb-2" v-for="(role, key) in roles" :key="key">
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

        <!-- Role -->
		<div class="col-xs-12 col-md-9">
            <transition name="animation-fade" mode="out-in" appear>
                <section v-if="role" class="container py-4">
                    <!-- Header -->
                    <div class="">
                        <h2><i class="far fa-tag"></i> {{ form.displayname ?? 'New Role' }}</h2>
                        <p class="text-muted"> {{ form.tag ?? '@new-role' }}</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="createOrUpdate">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(key, value) in permissions" :key="value">
                                        <td>{{ $t(`permissions.${value}.title`) }} <i class="text-muted">{{ $t(`permissions.${value}.description`) }}</i></td>
                                        <td>
                                            <select class="form-control p-2" v-model="form.permissions[value]">
                                                <option :value="true">Yes</option>
                                                <option :value="role.permissions[value] ? '0' : false" :selected="!role.permissions || !role.permissions[value]">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Alert -->
                        <div v-if="!this.search(this.role, this.form)" class="alert alert-dark alert-fixed">
                            <div class="d-flex">
                                <p class="p-1">Heads up — you have unsaved changes!</p>

                                <!-- Submit Button -->
                                <div class="ml-auto">
                                    <a @click.prevent="setRole(this.role)" class="mx-3">Reset</a>

                                    <v-button class="btn-success btn-small">
                                        Save Changes
                                    </v-button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </transition>
		</div>
	</div>
</template>

<script>
	import Form from '../../utils/vue-form'
	import { mapGetters } from 'vuex'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles,admin.role.edit'],

		data () {
			return {
				roles: null,
				role: null,
				form: new Form({
                    id: 0,
                    tag: '',
                    displayname: '',
                    permissions: {},
                    override: 0,
                    default: 0,
                }),
				permissions: {
                    "interact": true,
                    "user.user-logs": true,
                    "user.edit-profile": true,
                    "user.edit-password": true,
                    "admin": false,
                    "admin.users": false,
                    "admin.user.get": false,
                    "admin.user.edit": false,
                    "admin.user.roles": false,
                    "admin.user.roles.assign": false,
                    "admin.user.roles.delete": false,
                    "admin.user.logs": false,
                    "admin.roles": false,
                    "admin.role.edit": false
                }
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
				const { data } = await this.form.get('/api/v1/roles', {})

				this.roles = data
			},
            /**
             * Fetch a role by id
             *
             * @param   {number}  id
             *
             * @return  {null}
             */
			async fetchRole(id = false) {
				const { data, status } = await this.form.get(`/api/v1/role/${id}`, {})

                if (status == 200) {
                    this.setRole(data)
                } else {
                    this.$router.push({ name: 'admin.role.edit', params: { id: 1 } })
                }
			},
            /**
             * Set the active role from the given data
             *
             * @param   {object}  data
             *
             * @return  {null}
             */
            setRole(data) {
                this.role = data

                // sync the permission settings with the form data
                Object.keys(this.permissions).forEach(key => {
                    if (!this.role.permissions[key]) {
                        this.role.permissions[key] = false
                    }
                })

                this.resetForm(this.role)
            },
            /**
             * Check if the 2 given arrays are equal
             *
             * @param   {array}  arr1
             * @param   {array}  arr2
             *
             * @return  {boolean}
             */
            search(array, haystack) {
                let state = true

                Object.keys(array).forEach(key => {
                    if (haystack[key]) {
                        if (typeof array[key] !== 'object') {
                            if (array[key] !== haystack[key]) {
                                state = false
                            }
                        }

                        if (typeof array[key] === 'object') {
                            if (this.search(array[key], haystack[key]) === false) {
                                state = false
                            }
                        }
                    }
                })

                return state
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
             * Copy a Object to another object
             *
             * @param   {object}  object
             * @param   {object}  data
             *
             * @return  {null}
             */
            copyObject(object, data = null) {
                if(data == null) {
                    return false
                }

                Object.keys(data).forEach(key => {
                    if (typeof object[key] === 'object') {
                        if (object[key]) {
                            object[key] = {}
                        }

                        this.copyObject(object[key], data[key])
                    } else {
                        object[key] = data[key] ?? null
                    }
				})
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
                this.copyObject(this.form, data)
			},
            /**
             * Create a new role
             *
             * @return  {null}
             */
            createNewRole() {
                const role = {
                    id: 0,
                    tag: '@new-role',
                    displayname: 'New Role',
                    permissions: { ...this.permissions },
                    override: 0,
                    default: 0,
                }

                this.role = { ...role }
                this.roles = { ...this.roles, role }

                this.resetForm(this.role)
                this.$router.push({ name: 'admin.role.edit', params: { id: 0 } })
            },
            /**
             * Submit the Roles form
             *
             * @return  {null}
             */
			createOrUpdate() {
                if (this.form.id === 0) {
				    this.create()

                    return true
                }

                this.update()
			},
            create() {
                console.log('create')
            },
            update() {
                //const { data } = await this.form.get(`/api/v1/role/${id}`, {})
                console.log('update')
            }
		},

		computed: mapGetters({
			user: 'auth/user'
		}),

		watch: {
            async $route(to, from) {
                if (to.name !== 'admin.role.edit' || (this.role && this.role.id == to.params.id)) return

                this.role = null

                // test
                if (this.roles.role && to.params.id === '0') {
                    return this.$nextTick(() => this.setRole(this.roles.role))
                }

                return this.$nextTick(() => this.fetchRole(to.params.id))
            },
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
.alert-fixed {
    position: sticky;
    bottom: 30px;
    padding: 10px;
}
</style>
