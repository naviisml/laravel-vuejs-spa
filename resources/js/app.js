import { createApp } from 'vue'
import { i18n } from './utils/i18n'
import { router } from './utils/router'
import { store } from './utils/store'
import { components } from './components'

import App from './components/App.vue'

import './utils'

export const app = createApp(App)

app.use(router)
app.use(store)
app.use(i18n)

components.forEach(Component => {
	app.component(Component.name, Component)
})

app.mount('#app')
