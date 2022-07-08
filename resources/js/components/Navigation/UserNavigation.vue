<template>
	<nav v-if="user" class="nav-left nav-auth p-2" :class="{ 'nav-collapsed': isHidden }">
        <div class="d-flex flex-column" style="height: 100%">
            <!-- Profile -->
            <div class="profile p-1">
                <v-dropdown>
                    <!-- Title -->
                    <template v-slot:title>
                        <div class="row profile-content">
                            <div class="col-xs-4 col-lg-3">
                                <div class="profile-picture">
                                    <img src="https://st3.depositphotos.com/1767687/16607/v/450/depositphotos_166074422-stock-illustration-default-avatar-profile-icon-grey.jpg" />
                                </div>
                            </div>
                            <div class="col-xs-8 col-lg-9 label">
                                <div class="mt-2">
                                    <strong>Company</strong>

                                    <span class="tooltip-bottom float-right" aria-label="Switch Account">
                                        <i class="fal fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Content -->
                    <template v-slot:dropdown-content>
                        <div class="profile-selector">
                            <!-- Select other company profiles -->
                        </div>
                    </template>
                </v-dropdown>
            </div>

            <!-- Top Items -->
            <ul class="nav-list d-flex flex-grow-1">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'user.user-profile' }">
                        <span class="icon">
                            <i class="far fa-compass"></i>
                        </span>

                        <p class="label">Dashboard</p>
                    </router-link>
                </li>
            </ul>

            <!-- Bottom Items -->
            <ul class="nav-list">
			    <!-- Settings -->
				<v-dropdown :options="{ active: subIsActive(['/user/edit-']) && (isHidden == false), closeTrigger: (isHidden == false ? false : true) }">
					<!-- Title -->
					<template v-slot:title>
						<li class="nav-item">
                            <a class="nav-link">
                                <span class="icon">
                                    <i class="far fa-cog"></i>
                                </span>

                                <p class="label">
                                    Settings

                                    <span class="float-right pr-3">
                                        <i class="fal fa-chevron-down"></i>
                                    </span>
                                </p>
                            </a>
						</li>
					</template>

					<!-- Content -->
					<template v-slot:dropdown-content>
						<ul class="nav-dropdown-items">
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'user.edit-profile' }" >
									Profile
								</router-link>
							</li>
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'user.edit-password' }" >
									Password
								</router-link>
							</li>
						</ul>
					</template>
				</v-dropdown>

			    <!-- Admin -->
				<v-dropdown v-if="user.permissions['admin'] == true" :options="{ active: subIsActive(['/admin']) && (isHidden == false), closeTrigger: (isHidden == false ? false : true) }">
					<!-- Title -->
					<template v-slot:title>
						<li class="nav-item">
                            <a class="nav-link">
                                <span class="icon">
                                    <i class="far fa-shield"></i>
                                </span>

                                <p class="label">
                                    Admin

                                    <span class="float-right pr-3">
                                        <i class="fal fa-chevron-down"></i>
                                    </span>
                                </p>
                            </a>
						</li>
					</template>

					<!-- Content -->
					<template v-slot:dropdown-content>
						<ul class="nav-dropdown-items">
							<li v-if="user.permissions['admin.users'] == true" class="nav-item">
								<router-link class="nav-link" :to="{ name: 'admin.users' }" >
									Users
								</router-link>
							</li>
							<li v-if="user.permissions['admin.roles'] == true" class="nav-item">
								<router-link class="nav-link" :to="{ name: 'admin.roles' }" :class="{ 'router-link-exact-active': subIsActive(['/admin/role']) }">
									Roles
								</router-link>
							</li>
						</ul>
					</template>
				</v-dropdown>

                <li class="nav-item">
                    <a class="nav-link" @click.prevent="logout">
                        <span class="icon">
                            <i class="far fa-lock"></i>
                        </span>

                        <p class="label">Sign Out</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" @click.prevent="toggleNav">
                        <span class="icon" v-if="isHidden">
                            <i class="far fa-chevron-right"></i>
                        </span>
                        <span class="icon" v-else>
                            <i class="far fa-chevron-left"></i>
                        </span>

                        <p class="label">Collapse</p>
                    </a>
                </li>
            </ul>
        </div>
	</nav>
</template>

<script>
	import { mapGetters } from 'vuex'

	export default {
		name: 'Navigation',

        data() {
            return {
                isHidden: false
            }
        },

		computed: mapGetters({
			user: 'auth/user'
		}),

		methods: {
			async logout () {
				// Log out the user
				await this.$store.dispatch('auth/logout')

				// Redirect to home
				this.$router.push({ name: 'home' })
			},
            subIsActive(input) {
                const paths = Array.isArray(input) ? input : [input]

                return paths.some(path => {
                    return this.$route.path.indexOf(path) === 0 // current path starts with this path string
                })
            },
            toggleNav() {
                this.isHidden = !this.isHidden
            }
		}
	}
</script>
<style scoped>
.nav-left {
    box-shadow: none;
    border: none;
}
</style>
