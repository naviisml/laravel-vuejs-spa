function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	// Role Routes...
	{
		path: '/admin/role',
		component: Page('Admin/Roles'),
		children: [
			{
				path: '',
				name: 'admin.roles',
				redirect: {
					name: 'admin.role.edit',
					params: {
						id: 1
					}
				}
			},
			{
				path: ':id/edit-role',
				name: 'admin.role.edit',
				component: Page('Admin/Roles/Role')
			},
			{
				path: 'create',
				name: 'admin.role.create',
				component: Page('Admin/Roles/Role')
			}
		]
	},
]
