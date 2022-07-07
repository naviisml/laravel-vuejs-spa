import { createRouter, createWebHistory } from 'vue-router'
import { nextTick } from 'vue'
import { getGuards, callGuards } from './guards'
import { store } from './store'

// the routes
const routes = resolveRoutes(
	require.context('../routes', false, /.*\.js$/)
)

// the router instance
export const router = createRouterInstance()


/**
 * Create a new router instance
 *
 * @param   {Object}  config
 *
 * @return  {Router}
 */
function createRouterInstance() {
	let instance

	instance = createRouter({
		history: createWebHistory(),
		routes
	})

	instance.beforeEach(beforeEach)
	instance.afterEach(afterEach)

	return instance
}

/**
 * The beforeEach function for each route
 *
 * @param   {Object}  to
 * @param   {Object}  from
 * @param   {void}  next
 *
 * @return  {void}
 */
 async function beforeEach (to, from, next) {
	let components = []

	try {
		// Get the matched components and resolve them.
		components = await resolveComponents(
			to.matched
		)
	} catch (error) {
		if (/^Loading( CSS)? chunk (\d)+ failed\./.test(error.message)) {
			window.location.reload(true)
			return
		}
	}

	if (components.length === 0) {
		return next()
	}

	const routeGuards = await getGuards(components)

	let base = to.matched[0].components.default || false
	let layout = false

	if (typeof base === 'function')
		base = await base()
	if (typeof base === 'object')
		layout = base.layout

    if (components[components.length - 1].loading !== false) {
        await nextTick(() => store.dispatch('loading/state', { value: true }))
    }

	await callGuards(routeGuards, to, from, (...args) => {
		// Set the application layout only if "next()" was called with no args.
		if (args.length === 0) {
			window.App.setLayout(layout || '')
		}

		next(...args)
	})
}

/**
 * The afterEach function for each route
 *
 * @param   {Object}  to
 * @param   {Object}  from
 *
 * @return  {void}
 */
async function afterEach (to, from) {
    await nextTick(() => store.dispatch('loading/state', { value: false }))
}

/**
 * Resolve async components.
 *
 * @param  {Array} components
 * @return {Array}
 */
function resolveComponents (components) {
	let component

	return Promise.all(components.map(c => {
		component = c.components.default
		return typeof component === 'function' ? component() : component
	}))
}

/**
 * Resolve the route files
 *
 * @param   {Object}  files
 *
 * @return  {Array}
 */
function resolveRoutes(requireContext) {
	let routelist = []

	requireContext.keys().map(file =>
		[file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
	)
	.reduce((routes, [name, route]) => (
		routelist.push(...route.default)
	), {})

	return routelist
}
