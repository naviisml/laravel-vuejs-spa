import axios from 'axios'
import { store } from './store'

// Request interceptor
axios.interceptors.request.use(request => {
	const token = store.getters['auth/token']

	if (token) {
		request.headers.common.Authorization = `Bearer ${token}`
	}

	const locale = store.getters['auth/language']
	if (locale) {
		request.headers.common['Accept-Language'] = locale
	}

	// request.headers['X-Socket-Id'] = Echo.socketId()

	return request
})

// Response interceptor
axios.interceptors.response.use(response => response, error => {
	const { status } = error

	if (status === 401 && store.getters['auth/check']) {
		console.error('402 Error: Unauthorized')
	}

	if (status >= 500) {
		console.error('500 Error: Server Error')
	}

	return Promise.reject(error.response)
})
