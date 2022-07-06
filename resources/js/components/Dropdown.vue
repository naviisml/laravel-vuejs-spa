<template>
	<div class="dropdown" :class="{'is-active': state}">
		<span @click="this.toggle()">
			<slot name="title"/>
		</span>

		<div class="dropdown-content dropdown-right" :class="{'d-block': state}">
            <slot name="dropdown-content" />
        </div>
	</div>
</template>

<script>
	export default {
		name: 'VDropdown',

        props: {
            options: {
                type: Object,
                default: {
                    active: false,
                    closeTrigger: true
                }
            }
        },

		data() {
			return {
				element: null,
				state: false,
				event: null,
			}
		},

		mounted () {
            if (this.options.closeTrigger == true) {
			    document.addEventListener('click', this.triggerClose)
            }
		},

		beforeDestroy () {
            document.removeEventListener('click',this.triggerClose)
		},

		methods: {
			toggle() {
				if (this.state == false)
					this.open()
				else
					this.close()
			},
			triggerClose(e) {
				if (this.$el.contains(e.target)) {
                    return false
				}

                this.state = false
			},
			open () {
				this.state = true
			},
			close (e) {
				this.state = false
			}
		},

        watch: {
            options: function(value) {
                if (value.active) {
                    this.state = value.active
                }
            }
        }
	}
</script>

<style scoped>
.dropdown-content {
	text-align: left;
}
.dropdown-content::before,
.dropdown-content::after {
	display: none;
}
</style>
