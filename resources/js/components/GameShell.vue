<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { ArrowLeft } from '@lucide/vue';

defineProps<{
    kicker: string;
    title: string;
    copy: string;
}>();
</script>

<template>
    <section class="mx-auto min-h-[calc(100vh-5.5rem)] max-w-6xl px-5 pb-16 pt-10 sm:px-8">
        <RouterLink to="/" class="inline-flex items-center gap-2 text-sm text-white/60 transition hover:text-white">
            <ArrowLeft :size="16" />
            Back to landing
        </RouterLink>
        <div class="mt-6 max-w-2xl">
            <p class="text-xs tracking-[0.35em] text-[#f6d889]">{{ kicker }}</p>
            <h1 class="mt-3 font-display text-4xl font-bold sm:text-5xl">{{ title }}</h1>
            <p class="mt-3 text-base text-white/65 sm:text-lg">{{ copy }}</p>
        </div>
        <div class="mt-10">
            <slot />
        </div>
    </section>
</template>
