import { createI18n } from 'vue-i18n'

function loadMessages() {
    const locales = require.context('../languages', true, /[A-Za-z0-9-_,\s]+\.json$/i)
    const value = {}

    locales.keys().forEach(key => {
        const matched = key.match(/([A-Za-z0-9-_]+)\./i)

        if (matched && matched.length > 1) {
            const locale = matched[1]
            value[locale] = locales(key)
        }
    })

    return value
}

const messages = loadMessages()

export const i18n = createI18n({
	fallbackLocale: 'en',
	locale: 'en',
    messages
})
