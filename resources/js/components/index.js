import { app } from '../app'
import VTable from './VTable'
import VDropdown from './VDropdown'
import LoginWithOpenID from './Auth/LoginWithOpenID'
import LoginWithOAuth from './Auth/LoginWithOAuth'
import { Button } from '@naveldev/ui'

// Components that are registered globaly.
export const components = [
    LoginWithOpenID,
    LoginWithOAuth,
    VDropdown,
    VTable,
	Button
]
