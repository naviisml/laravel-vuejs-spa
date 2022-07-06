function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/',
		name: 'home',
		component: Page('HomePage')
	},
]
