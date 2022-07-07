<template>
	<div v-if="data">
		<table class="table table-responsive">
			<thead>
				<tr>
					<th v-for="(type) in data">{{ type }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(entry) in result">
					<td v-for="(type) in data">
						{{ entry[type] }}
					</td>
				</tr>
			</tbody>
		</table>
		<!-- Table Controls -->
		<div class="p-3">
			<div class="d-flex">
				<p class="text-muted">Page {{ page }} / {{ last_page }}</p>

				<!-- Table Actions -->
				<div class="ml-auto">
					<button class="btn btn-soft" @click="previousPage">
						<i class="far fa-long-arrow-left"></i>
					</button>
					<button class="btn btn-soft" @click="nextPage">
						<i class="far fa-long-arrow-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from 'axios'

	export default {
		name: 'VTable',

		props: {
			endpoint: {
				type: String,
				default: null
			},
			page: {
				type: Number,
				default: 1
			},
			data: {
				type: Array,
				default: []
			}
		},

		data () {
			return {
				last_page: 0,
				result: null,
				currentPage: 1,
			}
		},

		created () {
			this.currentPage = this.page
			this.getData(this.currentPage)
		},

		methods: {
			async getData (page) {
				const url = this.endpoint + '?page=' + page
				const { data } = await axios.get(url)

				this.result = data.data
				this.currentPage = data.current_page
				this.last_page = data.last_page
			},
			previousPage() {
				this.setPage(this.currentPage - 1)
			},
			nextPage() {
				this.setPage(this.currentPage + 1)
			},
			setPage(page) {
				if (page <= 0 || page > this.last_page) {
					return false, console.error(`Page ${page} doesn't exist.`)
				}
				this.getData(page)
			},
		}
	}
</script>
