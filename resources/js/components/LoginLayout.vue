<template>
    <router-view></router-view>
</template>

<script setup>
import {defineAsyncComponent} from 'vue';
const Login = defineAsyncComponent(()=>import('@/components/Login.vue'));
</script>

<style scoped>

</style>
