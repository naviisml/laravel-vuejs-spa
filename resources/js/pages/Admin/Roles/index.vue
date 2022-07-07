<template>
	<div class="row row-stretch">
		<div class="col-md-2">
			<roles-sub-navigation />
		</div>

		<div class="col-md-10">
			<router-view />
		</div>
	</div>
</template>

<script>
	import RolesSubNavigation from './Navigation.vue'
	
	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:admin,admin.roles'],

		components: {
			RolesSubNavigation
		},
	}
</script>

<style scoped>
.row-stretch
{
    grid-gap: 0;
    display: grid;
    grid-template-columns: repeat(12, calc(100% / 12));
	position: relative;
	height: 100%;
}
</style>