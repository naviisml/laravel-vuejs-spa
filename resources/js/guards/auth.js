import { store } from '../utils/store'

export default async (to, from, next) => {
	if (!store.getters['auth/check']) {
		return next({ name: 'login' })
	}

	next()
}
