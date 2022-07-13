<template>
	<div class="container py-5">
		<div class="card">
			<v-table :endpoint="endpoint" :page="page" :data="['action', 'metadata', 'ip_filtered', 'target_id', 'created_at']"></v-table>
		</div>
	</div>
</template>

<script>
	export default {
		layout: 'Auth',
		guards: ['auth', 'permissions:user.edit-profile'],

		data () {
			return {
				id: null,
				endpoint: null,
				page: 1
			}
		},

		created() {
			this.endpoint = '/api/v1/me/logs'
		}
	}
</script>
