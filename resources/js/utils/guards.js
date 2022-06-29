// the global guards
const globalGuards = ['check-auth']

// the guards
const guards = resolveGuards(
	require.context('../guards', false, /.*\.js$/)
)

/**
 * Resolve the guards
 *
 * @param   {Object}  files
 *
 * @return  {Array}
 */
function resolveGuards (requireContext) {
	return requireContext.keys().map(file =>
		[file.replace(/(.+)\/([^\/]+)\/|(^.\/)|(\.js$)/g, ''), requireContext(file)]
	).reduce((guards, [name, guard]) => (
		{ ...guards, [name]: guard.default }
	), {})
}

/**
 * Parse the guards to a list
 *
 * @param   {Object}  files
 *
 * @return  {Array}
 */
function parseGuards (guard) {
	if (typeof guard === 'function') {
		return { guard }
	}

	const [name, params] = guard.split(':')

	return { guard: name, params }
}

/**
 * Call the guards for a specific route
 *
 * @param   {Object}  files
 *
 * @return  {Array}
 */
export async function callGuards (list, to, from, next) {
	const stack = list.reverse()
	
	const _next = async (...args) => {
		// Stop if "_next" was called with an argument or the stack is empty.
		if (args.length > 0 || stack.length === 0) {
			return next(...args)
		}

		const { guard, params } = parseGuards(stack.pop())

		if (typeof guard === 'function') {
			await guard(to, from, _next, params)
		} else if (guards[guard]) {
			await guards[guard](to, from, _next, params)
		} else {
			throw Error(`Undefined guard [${guard}]`)
		}
	}

	await _next()
}

/**
 * Merge the the global middleware with the components middleware.
 *
 * @param  {Array} components
 * @return {Array}
 */
export async function getGuards (components) {
	const guardList = [...globalGuards]

	await components.forEach(c => {
		if (Array.isArray(c.guards)) {
			guardList.push(...c.guards)
		} else if (c.guards) {
			guardList.push(c.guards)
		}
	})
	
	return guardList
}