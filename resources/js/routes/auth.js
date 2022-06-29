function Container (path) {
	return () => import(/* webpackChunkName: '' */ `../containers/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/login',
		name: 'login',
		component: Container('LoginPage')
	},
	{
		path: '/register',
		name: 'register',
		component: Container('RegisterPage')
	},

	{
		path: '/user/user-profile',
		name: 'user.user-profile',
		component: Container('UserPage')
	},
]
