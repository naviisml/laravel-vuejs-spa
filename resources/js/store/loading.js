// state
export const state = {
	state: false
}

// getters
export const getters = {
	state: state => state.lang
}

// mutations
export const mutations = {
	["SET_STATE"] (state, { value }) {
		state.state = value
	},
}

// actions
export const actions = {
	state ({ commit, dispatch }, payload) {
		commit("SET_STATE", payload)
	},
}

export const namespaced = true
