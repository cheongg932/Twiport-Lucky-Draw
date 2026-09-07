<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import gsap from 'gsap';
import GameShell from '@/components/GameShell.vue';
import ProductVisual from '@/components/ProductVisual.vue';
import ResultModal from '@/components/ResultModal.vue';
import { drawPrize, fetchCatalog } from '@/api';
import type { Prize } from '@/types';

const spinning = ref(false);
const lever = ref<HTMLElement | null>(null);
const result = ref<Prize | null>(null);
const showResult = ref(false);
const catalog = ref<Prize[]>([]);
const reelEls = ref<(HTMLElement | null)[]>([null, null, null]);

const reelPrizes = computed(() => catalog.value.filter((prize) => prize.kind !== 'miss'));
const itemHeight = 160;

onMounted(async () => {
    const data = await fetchCatalog();
    catalog.value = data.prizes;
});

function setReel(index: number, el: unknown) {
    reelEls.value[index] = el instanceof HTMLElement ? el : null;
}

function prizeIndex(id: string) {
    const index = reelPrizes.value.findIndex((prize) => prize.id === id);
    return index >= 0 ? index : 0;
}

async function spin() {
    if (spinning.value || reelPrizes.value.length === 0) {
        return;
    }
    spinning.value = true;
    showResult.value = false;

    try {
        if (lever.value) {
            gsap.fromTo(lever.value, { rotate: 0 }, { rotate: 42, yoyo: true, duration: 0.35, repeat: 1, ease: 'power2.inOut' });
        }

        const draw = await drawPrize('slots');
        const reels = draw.reels ?? [draw.prize.id, draw.prize.id, draw.prize.id];
        const centerOffset = itemHeight;

        await Promise.all(
            reels.map((id, reel) => {
                const el = reelEls.value[reel];
                if (!el) {
                    return Promise.resolve();
                }
                const loops = 8 + reel * 2;
                const index = prizeIndex(id);
                const count = reelPrizes.value.length;
                const target = loops * count * itemHeight + index * itemHeight - centerOffset;
                return gsap.fromTo(el, { y: 0 }, {
                    y: -target,
                    duration: 2.2 + reel * 0.55,
                    ease: 'power4.out',
                }).then(() => {
                    gsap.set(el, { y: -(index * itemHeight - centerOffset) });
                });
            }),
        );

        result.value = draw.prize;
        showResult.value = true;
    } finally {
        spinning.value = false;
    }
}
</script>

<template>
    <GameShell
        kicker="GAME 03"
        title="Drop the reels."
        copy="A neon cabinet with iPhones, MacBooks and AirPods flying past the jackpot line. Match three to take it home."
    >
        <div class="mx-auto max-w-4xl">
            <div class="relative overflow-hidden rounded-[2.2rem] border border-white/10 bg-[#120818] p-5 shadow-[0_30px_80px_rgba(255,60,172,0.15)] sm:p-8">
                <div class="mb-5 text-center">
                    <p class="font-display text-3xl font-bold tracking-[0.4em] text-[#f6d889]">JACKPOT</p>
                    <p class="text-xs tracking-[0.35em] text-[#ff3cac]">TWIPORT SLOTS</p>
                </div>
                <div class="relative grid grid-cols-[1fr_auto] items-center gap-5">
                    <div class="relative overflow-hidden rounded-[1.4rem] border border-[#f6d889]/40 bg-black/40">
                        <div class="pointer-events-none absolute inset-x-0 top-1/2 z-10 h-[160px] -translate-y-1/2 border-y border-[#3de0ff]/50 bg-[#3de0ff]/5" />
                        <div class="grid grid-cols-3">
                            <div v-for="reel in 3" :key="reel" class="relative h-[480px] overflow-hidden border-white/5" :class="reel < 3 ? 'border-r' : ''">
                                <div
                                    class="will-change-transform"
                                    :ref="(el) => setReel(reel - 1, el)"
                                >
                                    <div
                                        v-for="loop in 60"
                                        :key="`${reel}-${loop}`"
                                        class="grid h-[160px] place-items-center overflow-hidden"
                                    >
                                        <div class="origin-center scale-[0.42]">
                                            <ProductVisual
                                                v-if="reelPrizes[(loop - 1) % Math.max(reelPrizes.length, 1)]"
                                                :kind="reelPrizes[(loop - 1) % reelPrizes.length].kind"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        ref="lever"
                        class="hidden h-48 w-8 origin-top rounded-full bg-gradient-to-b from-[#d0d5e0] to-[#7b8494] shadow-inner sm:block"
                        type="button"
                        :disabled="spinning"
                        @click="spin"
                    >
                        <span class="absolute -bottom-6 left-1/2 h-10 w-10 -translate-x-1/2 rounded-full bg-gradient-to-br from-[#ff3cac] to-[#f6d889]" />
                    </button>
                </div>
                <button
                    class="mt-8 w-full rounded-full bg-gradient-to-r from-[#ff3cac] via-[#f6d889] to-[#3de0ff] py-3.5 text-sm font-bold text-[#16080f]"
                    type="button"
                    :disabled="spinning"
                    @click="spin"
                >
                    {{ spinning ? 'Reels in motion…' : 'Pull the lever' }}
                </button>
            </div>
        </div>
        <ResultModal :open="showResult" :prize="result" @close="showResult = false" @again="spin" />
    </GameShell>
</template>
