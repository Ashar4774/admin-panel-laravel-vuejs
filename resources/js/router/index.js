import {createRouter, createWebHistory} from "vue-router";
import state from "@/state/index.js";

import Login from '../components/Login.vue';
import PanelLayout from '../components/PanelLayout.vue';
import Dashboard from '../components/pages/Dashboard.vue';
import StateOfAccount from '../components/pages/StateOfAccount.vue';
import ClientIndex from '../components/pages/clients/Index.vue';
import ClientStateOfAccount from '../components/pages/clients/StateOfAccount.vue';
import InvoiceIndex from '../components/pages/invoices/Index.vue';
import UserProfile from '../components/pages/UserProfile.vue';

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
        path: '/client/state_of_account/:id',
        name: 'client.state_of_account',
        component: ClientStateOfAccount,
        props: true,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true,
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
    {
        path: '/state_of_account',
        name: 'state_of_account',
        component: StateOfAccount,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    },
    {
        path: '/user_profile',
        name: 'user_profile',
        component: UserProfile,
        meta: {
            layout: 'PanelLayout',
            requiresAuth: true
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active'
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
