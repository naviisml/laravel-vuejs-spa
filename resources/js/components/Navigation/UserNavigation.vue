<template>
	<nav v-if="user" class="nav-left nav-dark">
        <div class="d-flex flex-column" style="height: 100%">
            <!-- Branding -->
            <h3 class="nav-branding p-3">Branding</h3>

            <!-- Top Items -->
            <ul class="nav-list d-flex flex-grow-1">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'user.user-profile' }">
                        Dashboard
                    </router-link>
                </li>
            </ul>

            <!-- Bottom Items -->
            <ul class="nav-list">
			    <!-- Settings -->
				<v-dropdown :options="{ active: subIsActive(['/user/edit-']), closeTrigger: false }">
					<!-- Title -->
					<template v-slot:title>
						<li class="nav-item">
                            <a class="nav-link">
                                Settings

                                <span class="float-right pr-3">
                                    <i class="fal fa-chevron-down"></i>
                                </span>
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

                <li class="nav-item">
                    <a class="nav-link" @click.prevent="logout">
                        Sign Out
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
            }
		}
	}
</script>
