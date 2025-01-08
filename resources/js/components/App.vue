<template>
    <component :is="layout">
        <router-view></router-view>
    </component>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

import LoginLayout from '@/components/LoginLayout.vue';
import PanelLayout from '@/components/PanelLayout.vue';

// Map layout names to layout components
const layoutComponents = {
    LoginLayout,
    PanelLayout
};

const route = useRoute();

// Compute the current layout based on the route's meta field
const layout = computed(() => layoutComponents[route.meta.layout] || PanelLayout);
</script>

