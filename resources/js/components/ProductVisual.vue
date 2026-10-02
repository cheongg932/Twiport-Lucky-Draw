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

const frameClass = computed(
    () =>
        ({
            sm: 'h-28 w-full',
            md: 'h-40 w-full',
            lg: 'mx-auto h-72 w-full max-w-[220px]',
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
        class="grid place-items-center overflow-hidden rounded-2xl bg-gradient-to-b ring-1 ring-white/15"
        :class="[frameClass, plate]"
    >
        <svg viewBox="0 0 100 100" class="h-[92%] w-[92%]" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
            <PrizeGlyph :kind="kind" />
        </svg>
    </div>
</template>
