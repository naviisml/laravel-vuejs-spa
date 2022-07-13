import { store } from '../utils/store'

export default async (to, from, next) => {
    const user = store.getters['auth/user']

	if (!user) {
		return next({ name: 'login' })
	}

    if (user.permissions.interact != true) {
		return next({ name: '403' })
    }

	next()
}
