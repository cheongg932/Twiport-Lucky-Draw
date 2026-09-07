<script setup lang="ts">
import { nextTick, onMounted, ref } from 'vue';
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

async function prepare() {
    revealed.value = false;
    showResult.value = false;
    result.value = await drawPrize('scratch').then((data) => data.prize);
    await nextTick();
    paintFoil();
}

function paintFoil() {
    const el = canvas.value;
    if (!el) {
        return;
    }
    const ctx = el.getContext('2d');
    if (!ctx) {
        return;
    }

    const rect = el.getBoundingClientRect();
    el.width = rect.width * 2;
    el.height = rect.height * 2;
    ctx.scale(2, 2);

    const gradient = ctx.createLinearGradient(0, 0, rect.width, rect.height);
    gradient.addColorStop(0, '#d7dee8');
    gradient.addColorStop(0.35, '#f7f9fd');
    gradient.addColorStop(0.55, '#b7c0ce');
    gradient.addColorStop(1, '#8e99ab');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, rect.width, rect.height);

    ctx.fillStyle = 'rgba(80, 90, 110, 0.18)';
    for (let i = 0; i < 18; i += 1) {
        ctx.save();
        ctx.translate(20 + i * 28, -40);
        ctx.rotate(-0.45);
        ctx.font = '700 18px Syne, sans-serif';
        ctx.fillText('TWIPORT  LUCKY  DRAW', 0, 80 + (i % 3) * 70);
        ctx.restore();
    }

    ctx.fillStyle = '#5c6578';
    ctx.font = '700 22px Syne, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('SCRATCH TO REVEAL', rect.width / 2, rect.height / 2);
}

function pos(event: PointerEvent) {
    const el = canvas.value;
    if (!el) {
        return { x: 0, y: 0 };
    }
    const rect = el.getBoundingClientRect();
    return { x: event.clientX - rect.left, y: event.clientY - rect.top };
}

function scratchAt(x: number, y: number) {
    const el = canvas.value;
    const ctx = el?.getContext('2d');
    if (!el || !ctx || revealed.value) {
        return;
    }
    ctx.save();
    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(x, y, 28, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();
    measure();
}

function measure() {
    const el = canvas.value;
    const ctx = el?.getContext('2d');
    if (!el || !ctx) {
        return;
    }
    const { data } = ctx.getImageData(0, 0, el.width, el.height);
    let clear = 0;
    for (let i = 3; i < data.length; i += 4) {
        if (data[i] < 20) {
            clear += 1;
        }
    }
    if (clear / (data.length / 4) > 0.52) {
        reveal();
    }
}

function reveal() {
    if (revealed.value) {
        return;
    }
    revealed.value = true;
    const el = canvas.value;
    const ctx = el?.getContext('2d');
    if (el && ctx) {
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
        copy="A holographic card with an iPhone waiting under the silver. Drag to scratch. Reveal at fifty percent."
    >
        <div class="mx-auto grid max-w-4xl items-center gap-10 lg:grid-cols-2">
            <div ref="card" class="relative">
                <div class="glass-panel overflow-hidden rounded-[2rem] p-4">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-[1.5rem] bg-[#12141c]">
                        <div class="absolute inset-0 grid place-items-center">
                            <div v-if="result" class="text-center">
                                <ProductVisual :kind="result.kind" />
                                <p class="mt-3 font-display text-xl">{{ result.name }}</p>
                                <p class="text-[#f6d889]">{{ result.value }}</p>
                            </div>
                        </div>
                        <canvas
                            ref="canvas"
                            class="absolute inset-0 h-full w-full cursor-crosshair touch-none"
                            @pointerdown="scratching = true; scratchAt(pos($event).x, pos($event).y)"
                            @pointermove="scratching && scratchAt(pos($event).x, pos($event).y)"
                            @pointerup="scratching = false"
                            @pointerleave="scratching = false"
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
                    Under this card could be an iPhone 16 Pro, AirPods Pro, or a Twiport voucher. The foil is the ritual —
                    keep scratching until the prize can’t hide.
                </p>
                <button
                    class="mt-8 rounded-full border border-white/15 px-5 py-3 text-sm"
                    type="button"
                    @click="prepare"
                >
                    New card
                </button>
            </div>
        </div>
        <ResultModal :open="showResult" :prize="result" @close="showResult = false" @again="prepare" />
    </GameShell>
</template>
