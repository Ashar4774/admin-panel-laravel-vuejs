<template>
    <Sidebar />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <Navbar />
        <router-view></router-view>
    </main>
</template>

<script setup>
import {defineAsyncComponent, onMounted} from 'vue';
import state from "@/state/index.js";
const Navbar = defineAsyncComponent(()=>import('@/components/layouts/Navbar.vue'));
const Sidebar = defineAsyncComponent(()=>import('@/components/layouts/Sidebar.vue'));


</script>

<style scoped>

</style>
