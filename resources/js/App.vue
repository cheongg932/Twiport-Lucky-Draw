<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router';
import AmbientBackground from '@/components/AmbientBackground.vue';
import SiteHeader from '@/components/SiteHeader.vue';

const route = useRoute();
</script>

<template>
    <div class="relative min-h-screen">
        <AmbientBackground />
        <SiteHeader />
        <main class="relative z-10">
            <RouterView v-slot="{ Component }">
                <Transition name="page" mode="out-in">
                    <component :is="Component" :key="route.fullPath" />
                </Transition>
            </RouterView>
        </main>
    </div>
</template>
