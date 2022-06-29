function Container (path) {
	return () => import(/* webpackChunkName: '' */ `../containers/${path}`).then(m => m.default || m)
}

export default [
	{
		path: '/',
		name: 'home',
		component: Container('HomePage')
	},
]
