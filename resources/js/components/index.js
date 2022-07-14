import { app } from '../app'
import VTable from './VTable'
import VDropdown from './VDropdown'
import VLoginWithOAuth from './VLoginWithOAuth'
import { Button } from '@naveldev/ui'

// Components that are registered globaly.
export const components = [
    VLoginWithOAuth,
    VDropdown,
    VTable,
	Button
]
