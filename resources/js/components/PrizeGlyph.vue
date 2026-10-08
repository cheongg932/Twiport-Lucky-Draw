<script setup lang="ts">
import { useId } from 'vue';
import type { PrizeKind } from '@/types';

defineProps<{
    kind: PrizeKind;
}>();

const uid = useId().replace(/[^a-zA-Z0-9]/g, '');
</script>

<template>
    <g>
        <defs>
            <linearGradient :id="`iphone-body-${uid}`" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#d9d4ce" />
                <stop offset="45%" stop-color="#8d8882" />
                <stop offset="100%" stop-color="#4f4b47" />
            </linearGradient>
            <linearGradient :id="`iphone-screen-${uid}`" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#6d4dff" />
                <stop offset="55%" stop-color="#2a1658" />
                <stop offset="100%" stop-color="#ff3cac" />
            </linearGradient>
            <linearGradient :id="`mac-screen-${uid}`" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#8ecfff" />
                <stop offset="100%" stop-color="#16324f" />
            </linearGradient>
            <linearGradient :id="`mac-lid-${uid}`" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#f3f5f8" />
                <stop offset="100%" stop-color="#b8c0cc" />
            </linearGradient>
            <linearGradient :id="`ipad-screen-${uid}`" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#9ad7ff" />
                <stop offset="100%" stop-color="#243056" />
            </linearGradient>
            <linearGradient :id="`watch-face-${uid}`" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#1c2430" />
                <stop offset="100%" stop-color="#05070d" />
            </linearGradient>
            <linearGradient :id="`voucher-${uid}`" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#fff1b8" />
                <stop offset="100%" stop-color="#e0b84a" />
            </linearGradient>
            <radialGradient :id="`miss-${uid}`" cx="50%" cy="40%" r="60%">
                <stop offset="0%" stop-color="#5a6278" />
                <stop offset="100%" stop-color="#222636" />
            </radialGradient>
        </defs>

        <g v-if="kind === 'iphone'">
            <rect x="31" y="7" width="38" height="86" rx="10" :fill="`url(#iphone-body-${uid})`" />
            <rect x="34" y="10" width="32" height="80" rx="8" :fill="`url(#iphone-screen-${uid})`" />
            <rect x="43" y="13.5" width="14" height="5" rx="2.5" fill="#09090c" />
            <circle cx="54.5" cy="16" r="1.1" fill="#1d4ed8" />
            <text x="50" y="36" text-anchor="middle" fill="#fff" font-size="9" font-weight="700" font-family="Manrope, sans-serif">9:41</text>
            <rect x="39" y="42" width="8" height="8" rx="2" fill="#ffd36b" />
            <rect x="49" y="42" width="8" height="8" rx="2" fill="#3de0ff" />
            <rect x="39" y="52" width="8" height="8" rx="2" fill="#fff" />
            <rect x="49" y="52" width="8" height="8" rx="2" fill="#ff3cac" />
            <rect x="44" y="82" width="12" height="2.2" rx="1.1" fill="rgba(255,255,255,0.85)" />
            <rect x="29" y="28" width="2" height="8" rx="1" fill="#7a7570" />
            <rect x="29" y="40" width="2" height="12" rx="1" fill="#7a7570" />
            <rect x="69" y="36" width="2" height="16" rx="1" fill="#7a7570" />
        </g>

        <g v-else-if="kind === 'macbook'">
            <rect x="16" y="14" width="68" height="46" rx="4" :fill="`url(#mac-lid-${uid})`" />
            <rect x="19" y="17" width="62" height="38" rx="2" :fill="`url(#mac-screen-${uid})`" />
            <circle cx="50" cy="36" r="6" fill="rgba(255,255,255,0.25)" />
            <path d="M50 32.5 L52.2 37.2 H47.8 Z" fill="#f6d889" />
            <path d="M10 62 H90 L84 70 H16 Z" fill="#d5dbe4" />
            <rect x="10" y="60" width="80" height="4" rx="1" fill="#eef1f6" />
            <rect x="38" y="64" width="24" height="3.5" rx="1" fill="#9aa3b2" />
        </g>

        <g v-else-if="kind === 'ipad'">
            <rect x="24" y="8" width="46" height="84" rx="8" fill="#c9ced8" />
            <rect x="27" y="11" width="40" height="74" rx="5" :fill="`url(#ipad-screen-${uid})`" />
            <circle cx="47" cy="16" r="1.4" fill="#8b93a3" />
            <rect x="38" y="38" width="18" height="18" rx="5" fill="rgba(255,255,255,0.28)" />
            <rect x="42" y="88.5" width="10" height="1.8" rx="0.9" fill="#8b93a3" />
            <rect x="73" y="22" width="3.2" height="56" rx="1.6" fill="#e8eaef" />
            <rect x="73" y="18" width="3.2" height="6" rx="1.6" fill="#ff3cac" />
        </g>

        <g v-else-if="kind === 'watch'">
            <rect x="38" y="4" width="24" height="18" rx="6" fill="#ff7a3c" />
            <rect x="38" y="78" width="24" height="18" rx="6" fill="#c2410c" />
            <rect x="28" y="20" width="44" height="60" rx="14" fill="#d8dce4" />
            <rect x="32" y="24" width="36" height="52" rx="12" :fill="`url(#watch-face-${uid})`" />
            <circle cx="50" cy="50" r="14" fill="none" stroke="#ff7a3c" stroke-width="2.2" />
            <circle cx="50" cy="50" r="8" fill="none" stroke="#3de0ff" stroke-width="1.6" />
            <text x="50" y="54" text-anchor="middle" fill="#fff" font-size="8" font-weight="800" font-family="Manrope, sans-serif">9:41</text>
            <rect x="72" y="40" width="5" height="14" rx="2" fill="#c5ccd6" />
            <rect x="24" y="36" width="4" height="10" rx="1.5" fill="#ff7a3c" />
        </g>

        <g v-else-if="kind === 'airpods'">
            <ellipse cx="50" cy="28" rx="26" ry="11" fill="#f4f6fb" />
            <ellipse cx="50" cy="26" rx="20" ry="7" fill="#d5dae3" />
            <rect x="22" y="32" width="56" height="44" rx="16" fill="#f7f8fb" />
            <rect x="28" y="38" width="44" height="30" rx="12" fill="#c8ced8" />
            <ellipse cx="40" cy="50" rx="7.5" ry="9" fill="#fff" />
            <rect x="36.5" y="50" width="7" height="16" rx="3.5" fill="#fff" />
            <ellipse cx="60" cy="50" rx="7.5" ry="9" fill="#fff" />
            <rect x="56.5" y="50" width="7" height="16" rx="3.5" fill="#fff" />
            <circle cx="50" cy="72" r="1.6" fill="#3de0ff" />
        </g>

        <g v-else-if="kind === 'voucher'">
            <path
                d="M12 30 C12 22, 18 18, 26 18 H74 C82 18, 88 22, 88 30 C82 34, 82 42, 88 46 C88 54, 82 58, 74 58 H26 C18 58, 12 54, 12 46 C18 42, 18 34, 12 30 Z"
                :fill="`url(#voucher-${uid})`"
            />
            <line x1="30" y1="22" x2="30" y2="54" stroke="#3a2a08" stroke-width="1" stroke-dasharray="2 3" opacity="0.45" />
            <text x="58" y="36" text-anchor="middle" fill="#3a2a08" font-size="7" font-weight="800" font-family="Manrope, sans-serif">GIFT</text>
            <text x="58" y="50" text-anchor="middle" fill="#3a2a08" font-size="11" font-weight="800" font-family="Manrope, sans-serif">RM 200</text>
        </g>

        <g v-else>
            <circle cx="50" cy="50" r="28" :fill="`url(#miss-${uid})`" stroke="#8b90a5" stroke-width="3" />
            <path d="M42 42 L58 58 M58 42 L42 58" stroke="#fff" stroke-width="4" stroke-linecap="round" />
        </g>
    </g>
</template>
