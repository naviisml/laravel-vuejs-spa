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
		component: Page('Admin/EditUsers/ListUsers.vue')
	},

	{
		path: '/admin/user/:id',
		component: Page('Admin/EditUsers/User.vue'),
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
				component: Page('Admin/EditUsers/EditProfile.vue')
			},
			{
				path: 'user-logs',
				name: 'admin.user.user-logs',
				component: Page('Admin/EditUsers/UserLogs.vue')
			},
			{
				path: 'user-roles',
				name: 'admin.user.user-roles',
				component: Page('Admin/EditUsers/EditRoles.vue')
			}
		]
	},
]
