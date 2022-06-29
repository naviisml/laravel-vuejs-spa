<template>
	<div class="wrapper">
		<component :is="layout" v-if="layout" />
	</div>
</template>

<script>
	import { shallowRef,  ref, computed } from 'vue'

	// Load layout components dynamically.
	const requireContext = require.context('../../layouts/', false, /.*\.vue$/)

	const layouts = requireContext.keys()
	.map(file =>
		[file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
	)
	.reduce((components, [name, component]) => {
		components[name] = shallowRef(component.default || component)
		return components
	}, {})

	export default {
		el: '.wrapper',

		data: () => ({
			layout: null,
			defaultLayout: 'Default'
		}),

		metaInfo () {
			return {
				title: title,
				titleTemplate: `%s · Title`
			}
		},

		created () {
			window.App = this
		},

		methods: {
			/**
			 * Set the application layout.
			 *
			 * @param {String} layout
			 */
			setLayout (layout) {
				if (!layout || !layouts[layout]) {
					layout = this.defaultLayout
				}

				this.layout = layouts[layout]
			}
		}
	}

	/**
	 * Dynamically load the layouts
	 *
	 * @param   {array}  files
	 *
	 * @return  {array}
	 */
	function getLayouts(files) {
		let layouts = []

		for (var key in files) {
			if (files.hasOwnProperty(key)) {
				let file = files[key]
				
				layouts[key.replace(/(^..\/layouts\/)|(\.vue$)/g, '')] = shallowRef(file.default)
			}
		}

		return layouts
	}
</script>
