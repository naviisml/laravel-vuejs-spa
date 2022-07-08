function Page (path) {
	return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
	// Admin Routes...
	{
		path: '/admin/dashboard',
		name: 'admin.dashboard',
		redirect: {
			name: 'admin.users'
		}
	},

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

	// Users Routes...
	{
		path: '/admin/users',
		name: 'admin.users',
		component: Page('Admin/Users/List')
	},

	{
		path: '/admin/user/:id',
		component: Page('Admin/Users'),
		children: [
			{
				path: '',
				redirect: {
					name: 'admin.users'
				}
			},
			{
				path: 'user-profile',
				name: 'admin.user.user-profile',
				component: Page('Admin/Users/EditProfile')
			},
			{
				path: 'user-logs',
				name: 'admin.user.user-logs',
				component: Page('Admin/Users/UserLogs')
			},
			{
				path: 'user-roles',
				name: 'admin.user.user-roles',
				component: Page('Admin/Users/EditRoles')
			}
		]
	},
]
