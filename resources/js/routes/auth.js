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
		path: '/user/user-dashboard',
		name: 'user.user-dashboard',
		component: Page('DashboardPage')
	},

	{
		path: '/user/user-profile',
		name: 'user.user-profile',
		component: Page('UserPage')
	},

    // experimental: link discord and steam to your account
    {
		path: '/link',
		name: 'link',
		component: Page('LinkPage/Link')
	},
]
