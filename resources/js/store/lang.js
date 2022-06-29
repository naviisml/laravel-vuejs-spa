// state
export const state = {
	language: 0
}

// getters
export const getters = {
	language: state => state.lang
}

// mutations
export const mutations = {
	["SET_LANGUAGE"] (state, { language }) {
		state.language = language
	},
}

// actions
export const actions = {
	setLanguage ({ commit, dispatch }, payload) {
		commit("SET_LANGUAGE", payload)
	},
}

export const namespaced = true