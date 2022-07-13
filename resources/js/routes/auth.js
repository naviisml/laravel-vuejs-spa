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

    // User
	{
		path: '/user/user-profile',
		name: 'user.user-profile',
		component: Page('User/ProfilePage')
	},

    // Settings
	{
		path: '/user/edit-profile',
		name: 'user.edit-profile',
		component: Page('Settings/EditProfile.vue')
	},

	{
		path: '/user/edit-password',
		name: 'user.edit-password',
		component: Page('Settings/EditPassword.vue')
	},

	{
		path: '/user/user-logs',
		name: 'user.user-logs',
		component: Page('Settings/UserLogs.vue')
	},

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
		path: '/admin/role/:id/edit-role',
        name: 'admin.role.edit',
		component: Page('Admin/Roles'),
	},

	// Users Routes...
	{
		path: '/admin/users',
		name: 'admin.users',
		component: Page('Admin/Users')
	},

	{
		path: '/admin/user/:id',
		component: Page('Admin/User'),
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
				component: Page('Admin/User/EditProfile')
			},
			{
				path: 'user-logs',
				name: 'admin.user.user-logs',
				component: Page('Admin/User/UserLogs')
			},
			{
				path: 'user-roles',
				name: 'admin.user.user-roles',
				component: Page('Admin/User/EditRoles')
			}
		]
	},
]
