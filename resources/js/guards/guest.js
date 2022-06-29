import { store } from '../utils/store'

export default (to, from, next) => {
	if (store.getters['auth/check']) {
		return next({ name: 'user.user-profile' })
	}

	next()
}
