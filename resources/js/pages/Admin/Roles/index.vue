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
                            <strong>New Role</strong> <i>@new-role</i>
                        </router-link>
                    </li>
                </ul>
            </nav>
		</div>

		<div class="col-xs-12 col-md-9">
			<router-view />
		</div>
	</div>
</template>

<script>
	import axios from 'axios'
	import { mapGetters } from 'vuex'

	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles'],

		data () {
			return {
				roles: null
			}
		},

		mounted () {
			this.fetchRoles();
		},

		methods: {
			async fetchRoles() {
				const { data } = await axios.get(`/api/v1/roles`)

				this.roles = data
			},
		},

		computed: mapGetters({
			user: 'auth/user'
		})
	}
</script>

<style scoped>
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
