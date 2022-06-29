<template>
	<div class="wrapper">
		<navigation />

		<router-view />
	</div>
</template>

<script>
	import Navigation from '../components/Navigation/Navigation'

	export default {
		name: 'DefaultLayout',

		components: {
			Navigation
		}
	}
</script>
