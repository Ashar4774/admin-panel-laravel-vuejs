import './bootstrap';

import {createApp} from 'vue';
import App from './components/App.vue';
import router from "./router/index.js";

const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
createApp(App).use(router).mount('#app')
