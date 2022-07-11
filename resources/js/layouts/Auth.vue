<template>
    <loading ref="loading" />

	<div class="wrapper d-flex flex-row flex-grow-1">
		<user-navigation />

		<div class="wrapcontent flex-grow-1">
            <router-view v-slot="{ Component, route }">
                <!-- Use any custom transition and fallback to `fade` -->
                <transition :name="route.meta.transition || 'fade'">
                    <component :is="Component" />
                </transition>
            </router-view>
        </div>
	</div>
</template>

<script>
    import Loading from '../components/Loading'
	import UserNavigation from '../components/Navigation/UserNavigation'

	export default {
		name: 'DefaultLayout',

		components: {
            Loading,
			UserNavigation
		}
	}
</script>

<style scoped>
.wrapper {
	width: 100%;
	height: 100vh;
	position: relative;
    overflow: auto;
}
.wrapcontent {
    border: 1px solid rgb(245, 250, 253);
    background-color: rgb(245, 250, 253);
    border-top-left-radius: 30px;
    border-bottom-left-radius: 30px;
    touch-event: none;
    width: 100%;
    height: 100%;
    height: -webkit-fill-available;
    position: relative;
    overflow: auto;
}
</style>
