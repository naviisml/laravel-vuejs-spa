function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/',
		name: 'home',
		component: Page('HomePage')
	},
	{
		path: '/',
		name: '404',
		component: Page('ErrorPage')
	},
	{
		path: '/',
		name: '403',
		component: Page('ErrorPage')
	},
]
