import { store } from '../utils/store'

export default async (to, from, next) => {
	// Check if the user is logged in
	if (!store.getters['auth/check'] && store.getters['auth/token']) {
		try {
			await store.dispatch('auth/fetchUser')
		} catch (e) {}
	}

	next()
}
