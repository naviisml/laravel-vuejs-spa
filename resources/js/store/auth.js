import axios from 'axios'

// state
export const state = {
	user: null,
	token: localStorage.getItem('token') || null,
	check: false
}

// getters
export const getters = {
	user: state => state.user,
	token: state => state.token,
	check: state => state.user !== null
}

// mutations
export const mutations = {
	["TOKEN_SAVE"] (state, { token, remember }) {
		state.token = token
		localStorage.setItem('token', token)
	},

	["USER_FETCH"] (state, { user }) {
		state.user = user
		state.check = true
	},

	["USER_LOGOUT"] (state) {
		state.user = null
		state.token = null
		state.check = false

		localStorage.removeItem('token')
	},
}

// actions
export const actions = {
	/**
	 * Save the Bearer token
	 *
	 * @param   {Function}  commit
	 * @param   {Function}  dispatch
	 * @param   {Object}  payload
	 * @return  void
	 */
	saveToken ({ commit, dispatch }, payload) {
		commit("TOKEN_SAVE", payload)
	},
	/**
	 * Log the user out
	 *
	 * @param   {Function}  commit
	 * @return  void
	 */
	async logout ({ commit }) {
		try {
			await axios.post('/api/v1/logout')
		} catch (e) { }

		commit("USER_LOGOUT")
	},
	/**
	 * Fetch the user data
	 *
	 * @param   {Function}  commit
	 * @param   {Function}  dispatch
	 * @param   {Object}  payload
	 * @return  void
	 */
	async fetchUser({ commit, dispatch }, payload) {
		const { data, status } = await axios.get('/api/v1/me')

		if (status == 200) {
			commit("USER_FETCH", { user: data })
		} else {
			commit("USER_LOGOUT")
		}
	},
	/**
	 * Fetch the user data
	 *
	 * @param   {Function}  commit
	 * @param   {Function}  dispatch
	 * @param   {Object}  payload
	 * @return  void
	 */
	updateUser({ commit, dispatch }, payload) {
		commit("USER_FETCH", payload)
	},
	async fetchOAuthUrl (ctx, { provider }) {
		const { data } = await axios.post(`/api/v1/oauth/${provider}`)

		return data.url
	},
    async fetchOpenIDUrl (ctx, { provider }) {
		const { data } = await axios.post(`/api/v1/openid/${provider}`)

		return data.url
	}
}

export const namespaced = true
