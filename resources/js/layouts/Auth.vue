<template>
	<div class="wrapper d-flex flex-row flex-grow-1">
		<user-navigation />

		<div class="wrapcontent flex-grow-1">
            <router-view />
        </div>
	</div>
</template>

<script>
	import UserNavigation from '../components/Navigation/UserNavigation'

	export default {
		name: 'DefaultLayout',

		components: {
			UserNavigation
		}
	}
</script>

<style scoped>
.wrapper
{
	width: 100%;
	height: 100vh;
	position: relative;
	background-color: rgb(var(--color-dark));
	color: rgb(var(--color-light));
}

.wrapcontent
{
    background-color: rgb(var(--color-light));
    color: rgb(var(--color-dark));
    border-bottom-left-radius: 15px;
    border-top-left-radius: 15px;
    touch-event: none;
    overflow: auto;
    width: 100%;
    height: 100%;
    height: -webkit-fill-available;
    position: relative;
    touch-event: none;
    overflow: auto;
    z-index: 0;
}

@media (max-width: 768px)
{
    .wrapcontent
    {
        border-radius: 0;
    }
}
</style>
