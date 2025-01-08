import {createRouter, createWebHistory} from "vue-router";
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
            layout: 'PanelLayout'
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
