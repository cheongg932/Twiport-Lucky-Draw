<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import gsap from 'gsap';
import GameShell from '@/components/GameShell.vue';
import ProductVisual from '@/components/ProductVisual.vue';
import ResultModal from '@/components/ResultModal.vue';
import { drawPrize, fetchCatalog } from '@/api';
import type { Prize } from '@/types';

const spinning = ref(false);
const rotation = ref(0);
const wheelEl = ref<HTMLElement | null>(null);
const result = ref<Prize | null>(null);
const showResult = ref(false);
const catalog = ref<Prize[]>([]);
const wheelIds = ref<string[]>([
    'iphone-16-pro',
    'airpods-pro',
    'miss',
    'watch-ultra',
    'macbook-air',
    'ipad-pro',
    'voucher',
    'miss',
]);

const segments = computed(() =>
    wheelIds.value.map((id) => catalog.value.find((prize) => prize.id === id)).filter(Boolean) as Prize[],
);

const slice = computed(() => 360 / Math.max(segments.value.length, 1));

const colors = ['#2a1840', '#123044', '#3a2a12', '#14220f', '#401828', '#10243a', '#2d2408', '#1a1030'];

function polar(cx: number, cy: number, r: number, a: number) {
    const rad = ((a - 90) * Math.PI) / 180;
    return [cx + r * Math.cos(rad), cy + r * Math.sin(rad)];
}

function slicePath(index: number) {
    const start = index * slice.value;
    const end = start + slice.value;
    const [x1, y1] = polar(200, 200, 188, start);
    const [x2, y2] = polar(200, 200, 188, end);
    const large = slice.value > 180 ? 1 : 0;
    return `M200,200 L${x1},${y1} A188,188 0 ${large} 1 ${x2},${y2} Z`;
}

function labelPos(index: number) {
    const [x, y] = polar(200, 200, 118, index * slice.value + slice.value / 2);
    return { x, y, rotate: index * slice.value + slice.value / 2 };
}

onMounted(async () => {
    const data = await fetchCatalog();
    catalog.value = data.prizes;
    wheelIds.value = data.wheel;
});

async function spin() {
    if (spinning.value || segments.value.length === 0) {
        return;
    }
    spinning.value = true;
    showResult.value = false;

    const draw = await drawPrize('spin');
    const index = draw.segment ?? segments.value.findIndex((item) => item.id === draw.prize.id);
    const safeIndex = index >= 0 ? index : 0;
    const extra = 360 * 6;
    const target = extra + (360 - (safeIndex * slice.value + slice.value / 2));
    const next = rotation.value + target;

    gsap.to(wheelEl.value, {
        rotation: next,
        transformOrigin: '50% 50%',
        duration: 4.6,
        ease: 'power4.out',
        onComplete: () => {
            rotation.value = next;
            result.value = draw.prize;
            showResult.value = true;
            spinning.value = false;
        },
    });
}
</script>

<template>
    <GameShell
        kicker="GAME 01"
        title="Spin the gold wheel."
        copy="Eight slices. iPhones, MacBooks, AirPods — and the occasional almost. Watch it ease into destiny."
    >
        <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="relative mx-auto w-full max-w-[440px]">
                <div class="absolute inset-0 rounded-full bg-[conic-gradient(from_0deg,#f6d889,#ff3cac,#3de0ff,#f6d889)] opacity-40 blur-2xl" />
                <div class="relative aspect-square">
                    <div class="absolute left-1/2 top-[-14px] z-20 -translate-x-1/2">
                        <div class="h-0 w-0 border-x-[14px] border-t-[28px] border-x-transparent border-t-[#f6d889] drop-shadow-[0_8px_16px_rgba(246,216,137,0.55)]" />
                    </div>
                    <div
                        v-for="n in 16"
                        :key="n"
                        class="absolute left-1/2 top-1/2 h-2 w-2 -translate-x-1/2 rounded-full bg-[#f6d889]"
                        :class="spinning ? 'animate-pulse' : ''"
                        :style="{
                            transform: `rotate(${(n - 1) * 22.5}deg) translateY(-210px)`,
                        }"
                    />
                    <svg
                        ref="wheelEl"
                        viewBox="0 0 400 400"
                        class="relative h-full w-full cursor-pointer drop-shadow-[0_30px_50px_rgba(0,0,0,0.45)]"
                        @click="spin"
                    >
                        <circle cx="200" cy="200" r="198" fill="#0b0c12" />
                        <path
                            v-for="(segment, index) in segments"
                            :key="`${segment.id}-${index}`"
                            :d="slicePath(index)"
                            :fill="colors[index % colors.length]"
                            stroke="rgba(246,216,137,0.35)"
                            stroke-width="2"
                        />
                        <g v-for="(segment, index) in segments" :key="`label-${index}`">
                            <text
                                :x="labelPos(index).x"
                                :y="labelPos(index).y"
                                text-anchor="middle"
                                dominant-baseline="middle"
                                fill="#f6d889"
                                font-size="11"
                                font-weight="700"
                                :transform="`rotate(${labelPos(index).rotate} ${labelPos(index).x} ${labelPos(index).y})`"
                            >
                                {{ segment.kind === 'miss' ? 'NEXT' : segment.name.split(' ')[0] }}
                            </text>
                        </g>
                        <circle cx="200" cy="200" r="42" fill="#101218" stroke="#f6d889" stroke-width="4" />
                        <text x="200" y="206" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">SPIN</text>
                    </svg>
                </div>
            </div>

            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs tracking-[0.3em] text-white/45">THIS WHEEL HIDES</p>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div v-for="segment in segments.filter((item, index, list) => list.findIndex((x) => x.id === item.id) === index)" :key="segment.id" class="rounded-2xl bg-white/5 p-3">
                        <div class="flex h-[108px] items-center justify-center overflow-hidden">
                            <ProductVisual :kind="segment.kind" size="sm" />
                        </div>
                        <p class="mt-1 text-center text-xs text-white/70">{{ segment.name }}</p>
                    </div>
                </div>
                <button
                    class="mt-6 w-full rounded-full bg-gradient-to-r from-[#f6d889] to-[#ffd36b] py-3.5 text-sm font-bold text-[#3a2a08] disabled:opacity-60"
                    type="button"
                    :disabled="spinning"
                    @click="spin"
                >
                    {{ spinning ? 'Spinning…' : 'Spin for an iPhone' }}
                </button>
            </div>
        </div>
        <ResultModal :open="showResult" :prize="result" @close="showResult = false" @again="spin" />
    </GameShell>
</template>
