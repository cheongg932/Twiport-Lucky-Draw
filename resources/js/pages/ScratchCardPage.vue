<script setup lang="ts">
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';
import GameShell from '@/components/GameShell.vue';
import ProductVisual from '@/components/ProductVisual.vue';
import ResultModal from '@/components/ResultModal.vue';
import { drawPrize } from '@/api';
import type { Prize } from '@/types';

const canvas = ref<HTMLCanvasElement | null>(null);
const card = ref<HTMLElement | null>(null);
const scratching = ref(false);
const revealed = ref(false);
const result = ref<Prize | null>(null);
const showResult = ref(false);
const foilReady = ref(false);
let context: CanvasRenderingContext2D | null = null;
let lastPoint: { x: number; y: number } | null = null;
let observer: ResizeObserver | null = null;
let moves = 0;
let foilWidth = 0;
let foilHeight = 0;

async function prepare() {
    revealed.value = false;
    showResult.value = false;
    foilReady.value = false;
    lastPoint = null;
    moves = 0;
    foilWidth = 0;
    foilHeight = 0;
    result.value = await drawPrize('scratch').then((data) => data.prize);
    await nextTick();
    await new Promise((resolve) => requestAnimationFrame(() => resolve(null)));
    paintFoil();
}

function getContext(): CanvasRenderingContext2D | null {
    const el = canvas.value;
    if (!el) {
        return null;
    }
    if (!context || context.canvas !== el) {
        context = el.getContext('2d', { willReadFrequently: true });
    }
    return context;
}

function paintFoil() {
    const el = canvas.value;
    const ctx = getContext();
    if (!el || !ctx || revealed.value) {
        return;
    }

    const rect = el.getBoundingClientRect();
    const width = Math.round(rect.width);
    const height = Math.round(rect.height);
    if (width < 40 || height < 40) {
        return;
    }
    if (foilReady.value && width === foilWidth && height === foilHeight) {
        return;
    }

    foilWidth = width;
    foilHeight = height;
    el.width = width;
    el.height = height;
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    const gradient = ctx.createLinearGradient(0, 0, el.width, el.height);
    gradient.addColorStop(0, '#d7dee8');
    gradient.addColorStop(0.35, '#f7f9fd');
    gradient.addColorStop(0.55, '#b7c0ce');
    gradient.addColorStop(1, '#8e99ab');
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, el.width, el.height);

    ctx.fillStyle = 'rgba(80, 90, 110, 0.18)';
    for (let i = 0; i < 18; i += 1) {
        ctx.save();
        ctx.translate(20 + i * 28, -40);
        ctx.rotate(-0.45);
        ctx.font = '700 18px Syne, sans-serif';
        ctx.fillText('LUCKY  DRAW', 0, 80 + (i % 3) * 70);
        ctx.restore();
    }

    ctx.fillStyle = '#5c6578';
    ctx.font = '700 22px Syne, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('SCRATCH TO REVEAL', el.width / 2, el.height / 2);
    foilReady.value = true;
}

function pos(event: PointerEvent) {
    const el = canvas.value;
    if (!el || !el.width || !el.height) {
        return { x: 0, y: 0 };
    }
    const rect = el.getBoundingClientRect();
    return {
        x: ((event.clientX - rect.left) / rect.width) * el.width,
        y: ((event.clientY - rect.top) / rect.height) * el.height,
    };
}

function startScratch(event: PointerEvent) {
    if (revealed.value || !foilReady.value) {
        return;
    }
    event.preventDefault();
    scratching.value = true;
    lastPoint = pos(event);
    (event.currentTarget as HTMLCanvasElement).setPointerCapture(event.pointerId);
    scratchTo(lastPoint.x, lastPoint.y, true);
}

function moveScratch(event: PointerEvent) {
    if (!scratching.value || revealed.value) {
        return;
    }
    const point = pos(event);
    scratchTo(point.x, point.y, false);
    lastPoint = point;
}

function endScratch() {
    scratching.value = false;
    lastPoint = null;
}

function scratchTo(x: number, y: number, tap: boolean) {
    const ctx = getContext();
    if (!ctx || revealed.value) {
        return;
    }
    ctx.globalCompositeOperation = 'destination-out';
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.lineWidth = 28;
    ctx.beginPath();
    if (tap || !lastPoint) {
        ctx.arc(x, y, 14, 0, Math.PI * 2);
        ctx.fill();
    } else {
        ctx.moveTo(lastPoint.x, lastPoint.y);
        ctx.lineTo(x, y);
        ctx.stroke();
    }
    moves += 1;
    if (moves % 6 === 0) {
        measure();
    }
}

function measure() {
    const el = canvas.value;
    const ctx = getContext();
    if (!el || !ctx || revealed.value || el.width < 40 || el.height < 40) {
        return;
    }
    const { data } = ctx.getImageData(0, 0, el.width, el.height);
    let clear = 0;
    let sampled = 0;
    for (let i = 3; i < data.length; i += 16) {
        sampled += 1;
        if (data[i] < 40) {
            clear += 1;
        }
    }
    if (sampled > 0 && clear / sampled > 0.52) {
        reveal(false);
    }
}

function reveal(fromButton: boolean) {
    if (revealed.value) {
        return;
    }
    revealed.value = true;
    scratching.value = false;
    const el = canvas.value;
    const ctx = getContext();
    if (el && ctx) {
        ctx.globalCompositeOperation = 'source-over';
        ctx.clearRect(0, 0, el.width, el.height);
    }
    window.setTimeout(() => {
        showResult.value = true;
    }, fromButton ? 0 : 700);
}

onMounted(() => {
    prepare();
    observer = new ResizeObserver(() => {
        if (!revealed.value) {
            paintFoil();
        }
    });
    if (card.value) {
        observer.observe(card.value);
    }
});

onUnmounted(() => {
    observer?.disconnect();
});
</script>

<template>
    <GameShell
        kicker="GAME 02"
        title="Scratch the foil."
        copy="Drag across the silver. You have to peel most of the foil before the prize pops — a tap will not count."
    >
        <div class="mx-auto grid max-w-4xl items-center gap-10 lg:grid-cols-2">
            <div class="relative">
                <div class="glass-panel overflow-hidden rounded-[2rem] p-4">
                    <div ref="card" class="relative aspect-[4/3] overflow-hidden rounded-[1.5rem] bg-[#12141c]">
                        <div class="absolute inset-0 flex items-center justify-center p-4">
                            <div v-if="result" class="flex max-w-[16rem] flex-col items-center text-center">
                                <ProductVisual :kind="result.kind" size="md" />
                                <p class="mt-3 font-display text-xl">{{ result.name }}</p>
                                <p class="text-[#f6d889]">{{ result.value }}</p>
                            </div>
                        </div>
                        <canvas
                            ref="canvas"
                            class="absolute inset-0 z-10 h-full w-full cursor-crosshair touch-none"
                            @pointerdown="startScratch"
                            @pointermove="moveScratch"
                            @pointerup="endScratch"
                            @pointercancel="endScratch"
                        />
                    </div>
                </div>
                <p class="mt-4 text-center text-sm text-white/50">
                    {{ revealed ? 'Prize unlocked.' : 'Scratch about half the card. One click will not reveal it.' }}
                </p>
            </div>
            <div>
                <p class="font-serif text-2xl italic text-white/80">Holographic luck, titanium stakes.</p>
                <p class="mt-4 text-white/60">
                    Under this card could be an iPhone 16 Pro, AirPods Pro, or a gift voucher. Keep dragging until the foil
                    is mostly gone — then the prize sits in the middle of the card.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button
                        class="rounded-full bg-gradient-to-r from-[#f6d889] to-[#ffd36b] px-5 py-3 text-sm font-bold text-[#3a2a08]"
                        type="button"
                        :disabled="!result || revealed"
                        @click="reveal(true)"
                    >
                        Peel it all
                    </button>
                    <button
                        class="rounded-full border border-white/15 px-5 py-3 text-sm"
                        type="button"
                        @click="prepare"
                    >
                        New card
                    </button>
                </div>
            </div>
        </div>
        <ResultModal :open="showResult" :prize="result" @close="showResult = false" @again="prepare" />
    </GameShell>
</template>
