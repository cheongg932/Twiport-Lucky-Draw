<script setup lang="ts">
import { RouterLink, useRoute } from 'vue-router';
import { Sparkles } from '@lucide/vue';

const route = useRoute();

const links = [
    { to: '/', label: 'Landing' },
    { to: '/spin', label: 'Spin Wheel' },
    { to: '/scratch', label: 'Scratch Card' },
    { to: '/slots', label: 'Slot Machine' },
];
</script>

<template>
    <header class="relative z-30 px-5 pt-5 sm:px-8">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 rounded-full border border-white/10 bg-white/5 px-4 py-3 shadow-[0_12px_40px_rgba(0,0,0,0.28)] backdrop-blur-xl sm:px-6">
            <RouterLink to="/" class="flex items-center gap-2.5">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-[#f6d889] to-[#ff3cac] text-[#05060b]">
                    <Sparkles :size="16" />
                </span>
                <span class="font-display text-sm font-bold tracking-[0.18em] text-white sm:text-base">TWIPORT</span>
            </RouterLink>

            <nav class="no-scrollbar flex max-w-[52vw] items-center gap-1 overflow-x-auto md:max-w-none">
                <RouterLink
                    v-for="link in links"
                    :key="link.to"
                    :to="link.to"
                    class="shrink-0 rounded-full px-3 py-1.5 text-xs text-white/70 transition hover:bg-white/10 hover:text-white sm:text-sm"
                    :class="{ 'bg-white/12 text-white': route.path === link.to }"
                >
                    {{ link.label }}
                </RouterLink>
            </nav>

            <RouterLink
                to="/spin"
                class="rounded-full bg-gradient-to-r from-[#f6d889] via-[#ffe7a8] to-[#ffd36b] px-4 py-2 text-xs font-bold tracking-wide text-[#3a2a08] shadow-[0_8px_24px_rgba(246,216,137,0.28)] sm:text-sm"
            >
                Play now
            </RouterLink>
        </div>
    </header>
</template>
