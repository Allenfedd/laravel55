import Main from '@/pages/layouts/Main'

export default [
    {
        path: '/admin/login',
        name: 'login',
        component: require('@/pages/auth/Login.vue'),
        meta: {requiresGuest: true}
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
            },
            {
                path: 'post',
                name: 'admin.post',
                component: require('@/pages/post/Index')
            },
            {
                path: 'post/create',
                name: 'admin.post.create',
                component: require('@/pages/post/Form'),
                meta: {isAdd: true}
            },
            {
                path: 'post/:id/edit',
                name: 'admin.post.edit',
                component: require('@/pages/post/Form'),
                meta: {isAdd: false}
            }
        ]
    },
    {
        path: '*',
        component: require('@/pages/errors/404')
    }
]