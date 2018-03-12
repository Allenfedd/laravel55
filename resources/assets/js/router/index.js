import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import store from '@/store'

Vue.use(VueRouter);

const router = new VueRouter({
    routes, //routes: routes
    mode: 'history',
    'linkActiveClass': 'active'
});

router.beforeEach((to, from, next) => {
    //if(to.meta.requiresAuth)
    if (to.matched.some(record => record.meta.requiresAuth)) { // record => { return record.meta.requiresAuth }
        if (!store.getters.authToken) {
            next({
                name: 'login',
                replace: true,
                /* query: {redirect: to.fullPath*} */
            })
        }
    }
    next();
});

export default router