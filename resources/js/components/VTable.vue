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
                        {{ truncate(entry[type], 40, '...')}}
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
            /**
             * Load the previous page
             *
             * @return  {null}
             */
			previousPage() {
				this.setPage(this.currentPage - 1)
			},
            /**
             * Load the next page
             *
             * @return  {null}
             */
			nextPage() {
				this.setPage(this.currentPage + 1)
			},
            /**
             * Get the data from a specific endpoint
             *
             * @param   {integer}  page
             *
             * @return  {null}
             */
			async getData (page) {
				const url = this.endpoint + '?page=' + page
				const { data } = await axios.get(url)

				this.result = data.data
				this.currentPage = data.current_page
				this.last_page = data.last_page
			},
            /**
             * Set the current page
             *
             * @param   {integer}  page
             *
             * @return  {null}
             */
			setPage(page) {
				if (page <= 0 || page > this.last_page) {
					return false, console.error(`Page ${page} doesn't exist.`)
				}

				this.getData(page)
			},
            /**
             * Truncate a given string, array or object to add a suffix of some sort if the
             * given string, array or object reaches a certain length.
             *
             * @param   {string|object|array}  value
             * @param   {string}  stop
             * @param   {string}  clamp
             *
             * @return  {string}
             */
            truncate(value, stop, clamp) {
                if (typeof value === 'array' || typeof value === 'object') {
                    value = JSON.stringify(value)
                }

                const text = value.toString()

                return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
            }
		},
	}
</script>
