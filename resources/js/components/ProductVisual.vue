<script setup lang="ts">
import { computed } from 'vue';
import type { PrizeKind } from '@/types';
import PrizeGlyph from '@/components/PrizeGlyph.vue';

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

const boxPx = computed(
    () =>
        ({
            sm: 112,
            md: 160,
            lg: 220,
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
        class="mx-auto grid shrink-0 place-items-center overflow-hidden rounded-2xl bg-gradient-to-b ring-1 ring-white/15"
        :class="plate"
        :style="{ width: `${boxPx}px`, height: `${boxPx}px` }"
    >
        <svg
            :width="boxPx"
            :height="boxPx"
            viewBox="0 0 100 100"
            class="block h-full w-full"
            preserveAspectRatio="xMidYMid meet"
            aria-hidden="true"
        >
            <PrizeGlyph :kind="kind" />
        </svg>
    </div>
</template>
