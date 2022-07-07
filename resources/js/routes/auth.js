function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/login',
		name: 'login',
		component: Page('Auth/LoginPage')
	},
	{
		path: '/register',
		name: 'register',
		component: Page('Auth/RegisterPage')
	},

	{
		path: '/user/user-profile',
		name: 'user.user-profile',
		component: Page('User/ProfilePage')
	},

    // Settings
	{
		path: '/user/edit-profile',
		name: 'user.edit-profile',
		component: Page('User/ProfilePage')
	},

	{
		path: '/user/edit-password',
		name: 'user.edit-password',
		component: Page('User/ProfilePage')
	},

    // experimental: link discord and steam to your account
    {
		path: '/link',
		name: 'link',
		component: Page('User/LinkPage')
	},
]
