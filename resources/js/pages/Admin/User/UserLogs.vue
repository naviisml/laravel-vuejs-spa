<template>
	<!-- Logs -->
	<section class="container py-5">
		<div class="card">
			<v-table :endpoint="'/api/v1/user/' + id + '/logs'" :page="page" :data="['action', 'metadata', 'ip_filtered', 'user_id', 'target_id', 'created_at']"></v-table>
		</div>
	</section>
</template>

<script>
	export default {
		guards: 'permissions:admin.user.logs',

		data () {
			return {
				id: null,
				page: 1
			}
		},

		props: {
			user: {
				type: Object,
				default: null
			}
		},

		created() {
			this.id = this.$route.params.id
		}
	}
</script>
