<script setup lang="ts">
import { computed } from 'vue';
import type { PrizeKind } from '@/types';

const props = withDefaults(
    defineProps<{
        kind: PrizeKind;
        compact?: boolean;
        size?: 'sm' | 'md' | 'lg';
    }>(),
    {
        compact: false,
    },
);

const resolved = computed(() => props.size ?? (props.compact ? 'md' : 'lg'));

const frameClass = computed(
    () =>
        ({
            sm: 'h-[108px] w-[96px]',
            md: 'h-[150px] w-[132px]',
            lg: 'h-[280px] w-[170px]',
        })[resolved.value],
);

const scaleClass = computed(
    () =>
        ({
            sm: 'scale-[0.30]',
            md: 'scale-[0.42]',
            lg: 'scale-[0.80]',
        })[resolved.value],
);
</script>

<template>
    <div class="relative grid place-items-center overflow-hidden" :class="frameClass">
        <div class="origin-center" :class="scaleClass">
            <div v-if="kind === 'iphone'" class="relative h-[340px] w-[168px]">
                <div class="absolute inset-0 rounded-[2.4rem] bg-gradient-to-br from-[#3a3a40] via-[#111114] to-[#050506] p-[7px] shadow-[0_30px_60px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.18)]">
                    <div class="relative h-full overflow-hidden rounded-[2rem] bg-[linear-gradient(180deg,#24143d_0%,#14233d_48%,#0b1220_100%)]">
                        <div class="absolute left-1/2 top-3 z-10 h-[18px] w-[72px] -translate-x-1/2 rounded-full bg-black shadow-[0_0_0_2px_#111]" />
                        <div class="absolute inset-x-5 top-12 text-center text-white">
                            <p class="text-[11px] tracking-[0.3em] text-white/60">LUCKY DRAW</p>
                            <p class="mt-3 font-display text-4xl font-bold">9:41</p>
                            <div class="mx-auto mt-8 h-24 w-24 rounded-[1.8rem] bg-gradient-to-br from-[#ff3cac]/80 to-[#3de0ff]/70 shadow-[0_12px_40px_rgba(255,60,172,0.35)]" />
                            <p class="mt-6 text-[11px] text-white/70">iPhone 16 Pro</p>
                        </div>
                        <div class="absolute inset-x-10 bottom-5 h-1.5 rounded-full bg-white/80" />
                    </div>
                </div>
                <div class="absolute -right-[3px] top-24 h-10 w-[3px] rounded-r bg-[#2a2a2e]" />
                <div class="absolute -left-[3px] top-20 h-8 w-[3px] rounded-l bg-[#2a2a2e]" />
                <div class="absolute -left-[3px] top-32 h-12 w-[3px] rounded-l bg-[#2a2a2e]" />
            </div>

            <div v-else-if="kind === 'macbook'" class="relative w-[280px]">
                <div class="rounded-t-2xl border border-white/10 bg-gradient-to-b from-[#d7dbe3] to-[#9aa3b2] p-2 shadow-[0_24px_50px_rgba(0,0,0,0.4)]">
                    <div class="relative overflow-hidden rounded-xl bg-[#0b1220] px-4 py-6">
                        <div class="absolute left-1/2 top-1.5 h-1.5 w-1.5 -translate-x-1/2 rounded-full bg-[#3a3a40]" />
                        <p class="text-center text-[10px] tracking-[0.4em] text-white/50">MACBOOK AIR</p>
                        <div class="mx-auto mt-4 h-16 w-28 rounded-xl bg-gradient-to-r from-[#3de0ff]/40 to-[#f6d889]/40" />
                    </div>
                </div>
                <div class="relative -mt-px h-3 rounded-b-xl bg-gradient-to-b from-[#cfd5de] to-[#8b93a3]">
                    <div class="absolute left-1/2 top-0 h-1 w-16 -translate-x-1/2 rounded-b bg-[#6f7684]" />
                </div>
            </div>

            <div v-else-if="kind === 'ipad'" class="relative h-[220px] w-[168px] rounded-[1.6rem] bg-gradient-to-br from-[#2c2c32] to-[#09090b] p-[7px] shadow-[0_24px_50px_rgba(0,0,0,0.4)]">
                <div class="flex h-full items-center justify-center overflow-hidden rounded-[1.2rem] bg-[linear-gradient(160deg,#1b1430,#102033)]">
                    <div class="text-center">
                        <div class="mx-auto h-14 w-14 rounded-2xl bg-gradient-to-br from-white/80 to-[#9ad7ff]" />
                        <p class="mt-3 text-[11px] tracking-[0.3em] text-white/70">iPAD PRO</p>
                    </div>
                </div>
            </div>

            <div v-else-if="kind === 'watch'" class="relative flex flex-col items-center">
                <div class="h-10 w-14 rounded-t-xl bg-gradient-to-b from-[#ff7a3c] to-[#c2410c]" />
                <div class="h-[148px] w-[128px] rounded-[2rem] bg-gradient-to-br from-[#3a3a40] to-[#111] p-2 shadow-[0_18px_40px_rgba(0,0,0,0.4)]">
                    <div class="flex h-full flex-col items-center justify-center rounded-[1.5rem] bg-[#0a0c12]">
                        <p class="text-[10px] tracking-[0.3em] text-[#ffb38a]">ULTRA</p>
                        <p class="font-display text-3xl font-bold text-white">9:41</p>
                        <p class="text-[10px] text-white/50">128 BPM</p>
                    </div>
                </div>
                <div class="h-10 w-14 rounded-b-xl bg-gradient-to-b from-[#c2410c] to-[#7c2d12]" />
            </div>

            <div v-else-if="kind === 'airpods'" class="relative h-[168px] w-[140px]">
                <div class="absolute left-1/2 top-8 h-28 w-28 -translate-x-1/2 rounded-[2rem] bg-gradient-to-b from-white to-[#c9d0dc] shadow-[0_18px_40px_rgba(0,0,0,0.3)]">
                    <div class="absolute left-1/2 top-3 h-1.5 w-8 -translate-x-1/2 rounded-full bg-[#9aa3b2]" />
                    <div class="absolute left-1/2 top-[52%] h-10 w-10 -translate-x-1/2 rounded-full border border-[#b7bec9] bg-white" />
                </div>
                <div class="absolute left-8 top-0 h-16 w-7 rounded-full bg-gradient-to-b from-white to-[#d7dbe3]" />
                <div class="absolute right-8 top-0 h-16 w-7 rounded-full bg-gradient-to-b from-white to-[#d7dbe3]" />
            </div>

            <div v-else-if="kind === 'voucher'" class="relative h-[150px] w-[230px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#f6d889] via-[#e7c36a] to-[#a67c2d] p-5 text-[#3a2a08] shadow-[0_18px_40px_rgba(246,216,137,0.28)]">
                <div class="foil-sheen pointer-events-none absolute inset-0 opacity-50" />
                <p class="text-[10px] tracking-[0.4em]">GIFT CARD</p>
                <p class="mt-3 font-display text-2xl font-bold">RM 200</p>
                <p class="mt-1 text-xs">Lucky draw voucher</p>
            </div>

            <div v-else class="grid h-[150px] w-[150px] place-items-center rounded-full border border-white/10 bg-white/5 text-center">
                <div>
                    <p class="font-display text-lg text-white/80">Almost</p>
                    <p class="text-xs text-white/50">Try another draw</p>
                </div>
            </div>
        </div>
    </div>
</template>
