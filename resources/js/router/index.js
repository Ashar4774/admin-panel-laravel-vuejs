import {createRouter, createWebHistory} from "vue-router";
import state from "@/state/index.js";

import Login from '../components/Login.vue';
import PanelLayout from '../components/PanelLayout.vue';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            layout: 'LoginLayout'
        }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: PanelLayout,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    LinkActiveClass: 'active'
});

router.beforeEach((to, from, next)=>{
    const isAuthenticated = state.getters.authStatus
    console.log("isAuthenticated :" + isAuthenticated)
    if(to.meta.requiresAuth && !isAuthenticated){
        console.log("if")
        next('/login')
    } else if(to.name === 'login' && isAuthenticated){
        console.log("else if")
        next('/dashboard')
    } else {
        console.log('else')
        next();
    }
})

export default router;
