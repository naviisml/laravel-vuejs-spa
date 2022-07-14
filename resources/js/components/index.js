import { app } from '../app'
import VTable from './VTable'
import VDropdown from './VDropdown'
import LoginWithOAuth from './Auth/LoginWithOAuth'
import { Button } from '@naveldev/ui'

// Components that are registered globaly.
export const components = [
    LoginWithOAuth,
    VDropdown,
    VTable,
	Button
]
