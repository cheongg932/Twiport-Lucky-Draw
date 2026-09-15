<script setup lang="ts">
import { nextTick, onMounted, ref } from 'vue';
import GameShell from '@/components/GameShell.vue';
import ProductVisual from '@/components/ProductVisual.vue';
import ResultModal from '@/components/ResultModal.vue';
import { drawPrize } from '@/api';
import type { Prize } from '@/types';

const canvas = ref<HTMLCanvasElement | null>(null);
const scratching = ref(false);
const revealed = ref(false);
const result = ref<Prize | null>(null);
const showResult = ref(false);
let context: CanvasRenderingContext2D | null = null;

async function prepare() {
    revealed.value = false;
    showResult.value = false;
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
    if (!el || !ctx) {
        return;
    }

    const rect = el.getBoundingClientRect();
    if (rect.width < 10 || rect.height < 10) {
        return;
    }

    el.width = Math.round(rect.width);
    el.height = Math.round(rect.height);
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
}

function pos(event: PointerEvent) {
    const el = canvas.value;
    if (!el) {
        return { x: 0, y: 0 };
    }
    const rect = el.getBoundingClientRect();
    const scaleX = el.width / rect.width;
    const scaleY = el.height / rect.height;
    return {
        x: (event.clientX - rect.left) * scaleX,
        y: (event.clientY - rect.top) * scaleY,
    };
}

function startScratch(event: PointerEvent) {
    if (revealed.value) {
        return;
    }
    scratching.value = true;
    (event.currentTarget as HTMLCanvasElement).setPointerCapture(event.pointerId);
    scratchAt(pos(event).x, pos(event).y);
}

function moveScratch(event: PointerEvent) {
    if (!scratching.value) {
        return;
    }
    scratchAt(pos(event).x, pos(event).y);
}

function endScratch() {
    scratching.value = false;
    measure(true);
}

function scratchAt(x: number, y: number) {
    const ctx = getContext();
    if (!ctx || revealed.value) {
        return;
    }
    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(x, y, 46, 0, Math.PI * 2);
    ctx.fill();
    measure(false);
}

function measure(force: boolean) {
    const el = canvas.value;
    const ctx = getContext();
    if (!el || !ctx || revealed.value) {
        return;
    }
    const { data } = ctx.getImageData(0, 0, el.width, el.height);
    let clear = 0;
    const total = data.length / 4;
    for (let i = 3; i < data.length; i += 16) {
        if (data[i] < 40) {
            clear += 1;
        }
    }
    const sampled = total / 4;
    if (clear / sampled > (force ? 0.12 : 0.18)) {
        reveal();
    }
}

function reveal() {
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
    showResult.value = true;
}

onMounted(prepare);
</script>

<template>
    <GameShell
        kicker="GAME 02"
        title="Scratch the foil."
        copy="A holographic card with an iPhone waiting under the silver. Drag to scratch until the prize peels open."
    >
        <div class="mx-auto grid max-w-4xl items-center gap-10 lg:grid-cols-2">
            <div class="relative">
                <div class="glass-panel overflow-hidden rounded-[2rem] p-4">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-[1.5rem] bg-[#12141c]">
                        <div class="absolute inset-0 grid place-items-center">
                            <div v-if="result" class="text-center">
                                <ProductVisual :kind="result.kind" size="md" />
                                <p class="mt-3 font-display text-xl">{{ result.name }}</p>
                                <p class="text-[#f6d889]">{{ result.value }}</p>
                            </div>
                        </div>
                        <canvas
                            ref="canvas"
                            class="absolute inset-0 h-full w-full cursor-crosshair touch-none"
                            @pointerdown="startScratch"
                            @pointermove="moveScratch"
                            @pointerup="endScratch"
                            @pointercancel="endScratch"
                        />
                    </div>
                </div>
                <p class="mt-4 text-center text-sm text-white/50">
                    {{ revealed ? 'Prize unlocked.' : 'Scratch the silver film with your cursor.' }}
                </p>
            </div>
            <div>
                <p class="font-serif text-2xl italic text-white/80">Holographic luck, titanium stakes.</p>
                <p class="mt-4 text-white/60">
                    Under this card could be an iPhone 16 Pro, AirPods Pro, or a gift voucher. The foil is the ritual —
                    keep scratching until the prize can’t hide.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button
                        class="rounded-full bg-gradient-to-r from-[#f6d889] to-[#ffd36b] px-5 py-3 text-sm font-bold text-[#3a2a08]"
                        type="button"
                        :disabled="!result || revealed"
                        @click="reveal"
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
