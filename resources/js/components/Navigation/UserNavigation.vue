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
			    <!-- Admin CP -->
				<v-dropdown>
					<!-- Title -->
					<template v-slot:title>
						<li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'user.user-profile' }" >
                                Admin CP

                                <span class="float-right pr-3">
                                    <i class="fal fa-chevron-down"></i>
                                </span>
                            </router-link>
						</li>
					</template>

					<!-- Content -->
					<template v-slot:dropdown-content>
						<ul class="nav-dropdown-items">
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'user.user-profile' }" >
									Users
								</router-link>
							</li>
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'user.user-profile' }" >
									Roles
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
			}
		}
	}
</script>

<style scoped>
.nav-left {
    z-index: 1;
    overflow-y: auto;
    overflow-x: hidden;
}
.nav-left .dropdown {
    display: block;
}
.nav-left .dropdown.is-active .btn {
    background-color: #000 !important;
}
.nav-left .dropdown .nav-dropdown-items .nav-item {
    border-left: 1px solid rgba(255, 255, 255, .5);
    text-align: left;
}
.nav-left .dropdown .nav-dropdown-items .nav-item .nav-link {
    padding: 12px !important;
    color: #333333 !important;
}
.nav-left ul.nav-list {
    width: 100%;
    padding-bottom: 10px !important;
    padding-right: 15px !important;
}
.nav-left ul.nav-list li.nav-btn {
    text-align: left;
    padding: 10px 20px 0 20px;
}
.nav-left ul.nav-list li.nav-btn .btn {
    --button-background-color: 11, 11, 11 !important;
    --button-foreground-color: 255, 255, 255 !important;
    --button-secondary-color: 255, 255, 255 !important;
    --button-border-color: 255, 255, 255 !important;
}
.nav-left .user-profile {
    background-color: rgba(0, 0, 0, );
    width: 100%;
}
.nav-left .user-profile .profile-picture {
    border-radius: 50%;
    overflow: hidden;
    height: 60px;
    width: 60px;
}
.nav-left .user-profile .profile-picture img {
    object-fit: cover;
    min-height: 100%;
}
.nav-left .user-profile strong {
    display: block;
    font-weight: bold;
}
@media (min-width: 768px) {
    nav.nav-left ul.nav-list {
        min-width: 250px;
    }
}
@media (max-width: 768px) {
    nav.nav-left ul.nav-list {
        min-width: 0;
    }
    nav.nav-left .nav-dropdown-items {
        border: 1px solid #000;
        background-color: #000;
        position: absolute;
        top: 100%;
        padding: 0;
        margin: 15px;
        min-width: 200px;
        left: 100%;
        top: 0;
    }
    nav.nav-left .nav-dropdown-items .nav-item {
        border: none;
        text-align: left;
        display: block;
    }
    nav.nav-left .nav-dropdown-items .nav-item .nav-link {
        padding: 15px;
    }
    nav.nav-left .user-profile, nav.nav-left .label {
        display: none;
    }
    nav.nav-left.is-active {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
    nav.nav-left.is-active .user-profile {
        display: block;
    }
    nav.nav-left.is-active .label {
        display: block;
    }
}
</style>
