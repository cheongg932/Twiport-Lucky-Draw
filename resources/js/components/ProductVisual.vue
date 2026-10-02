<script setup lang="ts">
import { computed, useId } from 'vue';
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

const uid = `pv${useId().replace(/[^a-zA-Z0-9]/g, '')}`;

const resolved = computed(() => props.size ?? (props.compact ? 'md' : 'lg'));

const frameClass = computed(
    () =>
        ({
            sm: 'h-[72px] w-[72px]',
            md: 'h-[120px] w-[120px]',
            lg: 'h-[250px] w-[160px]',
        })[resolved.value],
);
</script>

<template>
    <div class="relative grid place-items-center" :class="frameClass">
        <svg
            v-if="kind === 'iphone'"
            viewBox="0 0 80 164"
            class="h-full max-h-full w-auto drop-shadow-[0_8px_18px_rgba(0,0,0,0.35)]"
            aria-hidden="true"
        >
            <defs>
                <linearGradient :id="`${uid}-phoneBody`" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#6a6a72" />
                    <stop offset="45%" stop-color="#2a2a30" />
                    <stop offset="100%" stop-color="#111114" />
                </linearGradient>
                <linearGradient :id="`${uid}-phoneScreen`" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#5b3dff" />
                    <stop offset="55%" stop-color="#1b3a6b" />
                    <stop offset="100%" stop-color="#0b1220" />
                </linearGradient>
            </defs>
            <rect x="4" y="2" width="72" height="160" rx="16" :fill="`url(#${uid}-phoneBody)`" />
            <rect x="8" y="8" width="64" height="148" rx="12" :fill="`url(#${uid}-phoneScreen)`" />
            <rect x="26" y="12" width="28" height="8" rx="4" fill="#050506" />
            <text x="40" y="52" text-anchor="middle" fill="#fff" font-size="16" font-weight="700" font-family="Syne, sans-serif">9:41</text>
            <rect x="24" y="64" width="32" height="32" rx="10" fill="#ff3cac" opacity="0.9" />
            <rect x="28" y="144" width="24" height="3" rx="1.5" fill="#fff" opacity="0.85" />
        </svg>

        <svg
            v-else-if="kind === 'macbook'"
            viewBox="0 0 180 112"
            class="h-auto w-full max-h-full drop-shadow-[0_8px_18px_rgba(0,0,0,0.35)]"
            aria-hidden="true"
        >
            <rect x="18" y="4" width="144" height="88" rx="8" fill="#c5ccd6" />
            <rect x="24" y="10" width="132" height="72" rx="4" fill="#102033" />
            <circle cx="90" cy="16" r="2" fill="#4b5563" />
            <rect x="62" y="36" width="56" height="24" rx="6" fill="#3de0ff" opacity="0.45" />
            <rect x="8" y="92" width="164" height="12" rx="3" fill="#9aa3b2" />
            <rect x="72" y="92" width="36" height="5" rx="1" fill="#6f7684" />
        </svg>

        <svg
            v-else-if="kind === 'ipad'"
            viewBox="0 0 110 148"
            class="h-full max-h-full w-auto drop-shadow-[0_8px_18px_rgba(0,0,0,0.35)]"
            aria-hidden="true"
        >
            <rect x="6" y="4" width="98" height="140" rx="14" fill="#2c2c32" />
            <rect x="12" y="10" width="86" height="128" rx="10" fill="#1b1430" />
            <rect x="36" y="52" width="38" height="38" rx="10" fill="#c8e7ff" />
            <circle cx="55" cy="128" r="3" fill="#6b7280" />
        </svg>

        <svg
            v-else-if="kind === 'watch'"
            viewBox="0 0 90 150"
            class="h-full max-h-full w-auto drop-shadow-[0_8px_18px_rgba(0,0,0,0.35)]"
            aria-hidden="true"
        >
            <rect x="28" y="2" width="34" height="28" rx="8" fill="#ff7a3c" />
            <rect x="10" y="28" width="70" height="94" rx="22" fill="#2a2a30" />
            <rect x="16" y="34" width="58" height="82" rx="18" fill="#0a0c12" />
            <text x="45" y="72" text-anchor="middle" fill="#ffb38a" font-size="8" font-family="Syne, sans-serif">ULTRA</text>
            <text x="45" y="92" text-anchor="middle" fill="#fff" font-size="16" font-weight="700" font-family="Syne, sans-serif">9:41</text>
            <rect x="28" y="120" width="34" height="28" rx="8" fill="#c2410c" />
        </svg>

        <svg
            v-else-if="kind === 'airpods'"
            viewBox="0 0 120 120"
            class="h-full max-h-full w-auto drop-shadow-[0_8px_18px_rgba(0,0,0,0.35)]"
            aria-hidden="true"
        >
            <rect x="28" y="38" width="64" height="64" rx="22" fill="#f4f6fa" />
            <rect x="48" y="46" width="24" height="5" rx="2.5" fill="#9aa3b2" />
            <circle cx="60" cy="78" r="12" fill="#fff" stroke="#b7bec9" stroke-width="3" />
            <rect x="34" y="14" width="16" height="36" rx="8" fill="#e8edf4" />
            <rect x="70" y="14" width="16" height="36" rx="8" fill="#e8edf4" />
        </svg>

        <svg
            v-else-if="kind === 'voucher'"
            viewBox="0 0 160 96"
            class="h-auto w-full max-h-full drop-shadow-[0_8px_18px_rgba(246,216,137,0.28)]"
            aria-hidden="true"
        >
            <rect x="4" y="8" width="152" height="80" rx="12" fill="#f6d889" />
            <text x="20" y="36" fill="#3a2a08" font-size="8" letter-spacing="2" font-family="Syne, sans-serif">GIFT CARD</text>
            <text x="20" y="62" fill="#3a2a08" font-size="22" font-weight="700" font-family="Syne, sans-serif">RM 200</text>
        </svg>

        <svg
            v-else
            viewBox="0 0 120 120"
            class="h-full max-h-full w-auto"
            aria-hidden="true"
        >
            <circle cx="60" cy="60" r="46" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.18)" stroke-width="3" />
            <text x="60" y="58" text-anchor="middle" fill="#d7dbea" font-size="14" font-weight="700" font-family="Syne, sans-serif">Almost</text>
            <text x="60" y="76" text-anchor="middle" fill="#8b90a5" font-size="8" font-family="Manrope, sans-serif">try again</text>
        </svg>
    </div>
</template>
