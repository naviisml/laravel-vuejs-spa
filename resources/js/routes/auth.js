function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/login',
		name: 'login',
		component: Page('LoginPage')
	},
	{
		path: '/register',
		name: 'register',
		component: Page('RegisterPage')
	},

	{
		path: '/user/user-profile',
		name: 'user.user-profile',
		component: Page('UserPage')
	},

    // Settings
	{
		path: '/user/edit-profile',
		name: 'user.edit-profile',
		component: Page('UserPage')
	},

	{
		path: '/user/edit-password',
		name: 'user.edit-password',
		component: Page('UserPage')
	},

    // experimental: link discord and steam to your account
    {
		path: '/link',
		name: 'link',
		component: Page('LinkPage/Link')
	},
]
