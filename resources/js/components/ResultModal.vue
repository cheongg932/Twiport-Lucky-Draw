<script setup lang="ts">
import { computed, watch } from 'vue';
import { animate } from 'animejs';
import type { Prize } from '@/types';
import ProductVisual from '@/components/ProductVisual.vue';

const props = defineProps<{
    prize: Prize | null;
    open: boolean;
}>();

const emit = defineEmits<{
    close: [];
    again: [];
}>();

const won = computed(() => props.prize !== null && props.prize.kind !== 'miss');

watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) {
            return;
        }
        requestAnimationFrame(() => {
            const card = document.querySelector('.result-card');
            if (card) {
                animate(card, {
                    scale: [0.86, 1],
                    opacity: [0, 1],
                    ease: 'out(4)',
                    duration: 700,
                });
            }
        });
    },
);
</script>

<template>
    <Teleport to="body">
        <div v-if="open && prize" class="fixed inset-0 z-50 grid place-items-center px-4">
            <button class="absolute inset-0 bg-black/70 backdrop-blur-md" type="button" @click="emit('close')" />
            <div class="pointer-events-none absolute inset-0 overflow-hidden" v-if="won">
                <span
                    v-for="n in 28"
                    :key="n"
                    class="absolute h-2 w-2 rounded-sm"
                    :style="{
                        left: `${(n * 37) % 100}%`,
                        top: `-${(n * 13) % 30}%`,
                        background: ['#f6d889', '#ff3cac', '#3de0ff', '#fff'][n % 4],
                        animation: `float-y ${2 + (n % 5) * 0.3}s linear infinite`,
                        animationDelay: `${n * 0.08}s`,
                    }"
                />
            </div>
            <div class="result-card glass-panel relative w-full max-w-md rounded-[2rem] px-6 py-8 text-center">
                <p class="text-xs tracking-[0.4em] text-[#f6d889]">{{ won ? 'YOU WON' : 'NEXT ROUND' }}</p>
                <h2 class="mt-3 font-display text-3xl font-bold">{{ prize.name }}</h2>
                <p class="mt-2 text-sm text-white/65">{{ prize.tagline }}</p>
                <div class="mt-6 flex justify-center">
                    <ProductVisual :kind="prize.kind" compact />
                </div>
                <p class="mt-4 text-lg font-semibold text-[#f6d889]">{{ prize.value }}</p>
                <div class="mt-8 flex justify-center gap-3">
                    <button class="rounded-full border border-white/15 px-5 py-2.5 text-sm text-white/80" type="button" @click="emit('close')">
                        Close
                    </button>
                    <button
                        class="rounded-full bg-gradient-to-r from-[#f6d889] to-[#ffd36b] px-5 py-2.5 text-sm font-bold text-[#3a2a08]"
                        type="button"
                        @click="emit('again')"
                    >
                        Play again
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
