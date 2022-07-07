<template>
	<!-- Logs -->
	<section class="page-content p-5">
		<div class="card">
			<v-table :endpoint="endpoint" :page="page" :data="['action', 'metadata', 'ip_filtered', 'created_at']"></v-table>
		</div>
	</section>
</template>

<script>
	export default {
		guards: 'permissions:admin.user.logs',

		data () {
			return {
				id: null,
				endpoint: null,
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
			this.endpoint = '/api/v1/user/' + this.id + '/logs'
		}
	}
</script>
