import {createRouter, createWebHistory} from "vue-router";
import state from "@/state/index.js";

import Login from '../components/Login.vue';
import PanelLayout from '../components/PanelLayout.vue';
import Dashboard from '../components/pages/Dashboard.vue';
import ClientIndex from '../components/pages/clients/Index.vue';
import InvoiceIndex from '../components/pages/invoices/Index.vue';

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
        component: Dashboard,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    },
    {
        path: '/client',
        name: 'client',
        component: ClientIndex,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    },
    {
        path: '/invoice',
        name: 'invoice',
        component: InvoiceIndex,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    LinkActiveClass: 'active'
});

router.beforeEach((to, from, next)=>{
    const isAuthenticated = state.getters.authToken
    if(to.meta.requiresAuth && !isAuthenticated){
        next('/login')
    } else if(to.name === 'login' && isAuthenticated){
        next('/dashboard')
    } else {
        next();
    }
})

export default router;
