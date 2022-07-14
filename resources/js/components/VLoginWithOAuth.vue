<template>
	<v-button v-if="driver" class="btn btn-block btn-outline ms-auto" type="button" @click="login" :loading="loading">
		<slot name="label">
			<div class="d-flex flex-row justify-content-center">
				<div class="mr-2 pt-1" v-if="loading == false">
					<i class="fab" :class="`fa-${driver}`"></i>
				</div>

				<p>Continue with {{ driver }}</p>
			</div>
		</slot>
	</v-button>
</template>

<script>
	export default {
		name: 'LoginWithOauth',

		props: {
            driver: {
                type: String,
                default: ''
            },
			label: {
				type: String,
				default: ''
			},
            callback: {
                type: Function
            },
            user: {
                type: Object,
                default: null
            }
		},

		data() {
			return {
				loading: false
			}
		},

		mounted () {
			window.addEventListener('message', this.onMessage, false)
		},

		beforeDestroy () {
			window.removeEventListener('message', this.onMessage)
		},

		methods: {
			async login () {
				const newWindow = openWindow('', 'Login')

				let checkWindow = setInterval(() => {
				    this.loading = true

					if(newWindow.closed) {
						clearInterval(checkWindow)
						this.loading = false
					}
				}, 1000)

				const url = await this.$store.dispatch('auth/fetchOAuthUrl', {
					provider: this.driver
				})

				this.loading = true
				newWindow.location.href = url
			},

			onMessage (e) {
				// check hostnames??
				this.loading = false

                if (!e.data.token) {
                    return false
                }

				this.$store.dispatch('auth/saveToken', {
					token: e.data.token
				})

                if (this.callback) {
                    return this.callback()
                }
			}
		}
	}

	/**
	 * @param  {Object} options
	 * @return {Window}
	 */
	function openWindow (url, title, options = {}) {
		if (typeof url === 'object') {
			options = url
			url = ''
		}

		options = { url, title, width: 600, height: 720, ...options }

		const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screen.left
		const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screen.top
		const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
		const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height

		options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
		options.top = ((height / 2) - (options.height / 2)) + dualScreenTop

		const optionsStr = Object.keys(options).reduce((acc, key) => {
			acc.push(`${key}=${options[key]}`)
			return acc
		}, []).join(',')

		const newWindow = window.open(url, title, optionsStr)

		if (window.focus) {
			newWindow.focus()
		}

		return newWindow
	}
</script>
