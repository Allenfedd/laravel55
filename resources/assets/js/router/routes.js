import Main from '@/pages/layouts/Main'

export default [
    {
        path: '/admin/login',
        name: 'login',
        component: require('@/pages/auth/Login.vue'),
        // meta: {requiresGuest: true}
    },
    {
        path: '/admin',
        component: Main,
        redirect: '/admin/dashboard',
        meta: {requiresAuth: true},
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: require('@/pages/Dashboard')
            }
        ]
    },
    {
        path: '*',
        component: require('@/pages/errors/404')
    }
]