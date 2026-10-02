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
            sm: 'h-20 w-20',
            md: 'h-28 w-28',
            lg: 'h-60 w-36',
        })[resolved.value],
);

const plate = computed(
    () =>
        ({
            iphone: 'from-[#4c3dff]/40 to-[#151826]',
            macbook: 'from-[#9ad7ff]/30 to-[#151826]',
            ipad: 'from-[#b8c0ff]/30 to-[#151826]',
            watch: 'from-[#ff7a3c]/35 to-[#151826]',
            airpods: 'from-white/30 to-[#151826]',
            voucher: 'from-[#f6d889]/45 to-[#2a220c]',
            miss: 'from-white/10 to-[#151826]',
        })[props.kind],
);
</script>

<template>
    <div
        class="grid place-items-center rounded-2xl bg-gradient-to-b ring-1 ring-white/15"
        :class="[frameClass, plate]"
    >
        <svg
            v-if="kind === 'iphone'"
            viewBox="0 0 64 128"
            class="h-[78%] w-[78%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="8" y="2" width="48" height="124" rx="12" fill="#d8dce6" />
            <rect x="11" y="6" width="42" height="116" rx="10" fill="#5b3dff" />
            <rect x="22" y="10" width="20" height="6" rx="3" fill="#111" />
            <text x="32" y="48" text-anchor="middle" fill="#fff" font-size="14" font-weight="700">9:41</text>
            <rect x="20" y="58" width="24" height="24" rx="7" fill="#ff3cac" />
            <rect x="24" y="110" width="16" height="3" rx="1.5" fill="#fff" />
        </svg>

        <svg
            v-else-if="kind === 'macbook'"
            viewBox="0 0 140 90"
            class="h-[78%] w-[86%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="16" y="4" width="108" height="68" rx="6" fill="#e4e8ef" />
            <rect x="20" y="8" width="100" height="56" rx="3" fill="#1c3b63" />
            <rect x="50" y="28" width="40" height="16" rx="3" fill="#3de0ff" />
            <rect x="4" y="72" width="132" height="12" rx="3" fill="#c5ccd6" />
            <rect x="58" y="72" width="24" height="5" rx="1" fill="#7b8494" />
        </svg>

        <svg
            v-else-if="kind === 'ipad'"
            viewBox="0 0 86 112"
            class="h-[78%] w-[78%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="6" y="4" width="74" height="104" rx="10" fill="#d7dbe3" />
            <rect x="10" y="8" width="66" height="90" rx="7" fill="#243056" />
            <rect x="28" y="40" width="30" height="30" rx="8" fill="#9ad7ff" />
            <circle cx="43" cy="102" r="2.5" fill="#8b93a3" />
        </svg>

        <svg
            v-else-if="kind === 'watch'"
            viewBox="0 0 70 112"
            class="h-[82%] w-[70%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="22" y="2" width="26" height="20" rx="6" fill="#ff7a3c" />
            <rect x="8" y="20" width="54" height="72" rx="16" fill="#d8dce6" />
            <rect x="12" y="24" width="46" height="64" rx="13" fill="#141820" />
            <text x="35" y="52" text-anchor="middle" fill="#ffb38a" font-size="7">ULTRA</text>
            <text x="35" y="68" text-anchor="middle" fill="#fff" font-size="13" font-weight="700">9:41</text>
            <rect x="22" y="90" width="26" height="20" rx="6" fill="#c2410c" />
        </svg>

        <svg
            v-else-if="kind === 'airpods'"
            viewBox="0 0 90 90"
            class="h-[80%] w-[80%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="18" y="30" width="54" height="50" rx="16" fill="#f7f8fb" />
            <rect x="36" y="36" width="18" height="4" rx="2" fill="#9aa3b2" />
            <circle cx="45" cy="60" r="9" fill="#fff" stroke="#b7bec9" stroke-width="3" />
            <rect x="24" y="10" width="12" height="28" rx="6" fill="#eef1f6" />
            <rect x="54" y="10" width="12" height="28" rx="6" fill="#eef1f6" />
        </svg>

        <svg
            v-else-if="kind === 'voucher'"
            viewBox="0 0 120 72"
            class="h-[70%] w-[86%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <rect x="4" y="8" width="112" height="56" rx="10" fill="#f6d889" />
            <text x="16" y="30" fill="#3a2a08" font-size="8" font-weight="700">GIFT CARD</text>
            <text x="16" y="50" fill="#3a2a08" font-size="16" font-weight="700">RM 200</text>
        </svg>

        <svg
            v-else
            viewBox="0 0 90 90"
            class="h-[78%] w-[78%]"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <circle cx="45" cy="45" r="32" fill="#2a3144" stroke="#8b90a5" stroke-width="3" />
            <text x="45" y="43" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">Almost</text>
            <text x="45" y="58" text-anchor="middle" fill="#b4b8c7" font-size="7">try again</text>
        </svg>
    </div>
</template>
