import {createRouter, createWebHistory} from "vue-router";
import Login from '../components/Login.vue';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            layout: 'LoginLayout'
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
