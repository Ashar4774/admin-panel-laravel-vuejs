<template>
    <component :is="layout">
    </component>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import state from "@/state/index.js";
import axios from "@/axios.js";

import LoginLayout from '@/components/LoginLayout.vue';
import PanelLayout from '@/components/PanelLayout.vue';

// Map layout names to layout components
const layoutComponents = {
    LoginLayout,
    PanelLayout
};

const route = useRoute();

onMounted(()=>{
    state.dispatch('checkUserAuthentication')
});

// Compute the current layout based on the route's meta field
const layout = computed(() => layoutComponents[route.meta.layout] || PanelLayout);



// const authToken = computed(()=> state.state.token)
</script>

