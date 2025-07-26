import './bootstrap';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import $ from 'jquery';
import {createApp} from 'vue';
import App from './components/App.vue';
import router from "./router/index.js";

window.$ = window.jQuery = $;

createApp(App).use(router).mount('#app')
