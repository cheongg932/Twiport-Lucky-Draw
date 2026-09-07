<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const canvas = ref<HTMLCanvasElement | null>(null);
let frame = 0;
let raf = 0;

onMounted(() => {
    const el = canvas.value;
    if (!el) {
        return;
    }

    const ctx = el.getContext('2d');
    if (!ctx) {
        return;
    }

    const particles = Array.from({ length: 70 }, () => ({
        x: Math.random(),
        y: Math.random(),
        r: Math.random() * 1.8 + 0.4,
        s: Math.random() * 0.25 + 0.05,
        a: Math.random() * 0.5 + 0.15,
        hue: Math.random() > 0.72 ? '#3de0ff' : '#f6d889',
    }));

    const resize = () => {
        el.width = window.innerWidth;
        el.height = window.innerHeight;
    };

    const tick = () => {
        ctx.clearRect(0, 0, el.width, el.height);
        particles.forEach((p) => {
            p.y -= p.s / 220;
            if (p.y < -0.02) {
                p.y = 1.02;
                p.x = Math.random();
            }
            ctx.beginPath();
            ctx.fillStyle = p.hue;
            ctx.globalAlpha = p.a;
            ctx.arc(p.x * el.width, p.y * el.height, p.r, 0, Math.PI * 2);
            ctx.fill();
        });
        ctx.globalAlpha = 1;
        frame += 1;
        raf = requestAnimationFrame(tick);
    };

    resize();
    window.addEventListener('resize', resize);
    raf = requestAnimationFrame(tick);

    onUnmounted(() => {
        cancelAnimationFrame(raf);
        window.removeEventListener('resize', resize);
        void frame;
    });
});
</script>

<template>
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -left-24 top-[-12%] h-[42rem] w-[42rem] rounded-full bg-[radial-gradient(circle,rgba(255,60,172,0.22),transparent_64%)] blur-3xl" />
        <div class="absolute right-[-10%] top-[8%] h-[36rem] w-[36rem] rounded-full bg-[radial-gradient(circle,rgba(61,224,255,0.18),transparent_62%)] blur-3xl" />
        <div class="absolute bottom-[-18%] left-1/3 h-[40rem] w-[40rem] rounded-full bg-[radial-gradient(circle,rgba(246,216,137,0.16),transparent_60%)] blur-3xl" />
        <canvas ref="canvas" class="absolute inset-0 h-full w-full opacity-70" />
        <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(5,6,11,0.2),rgba(5,6,11,0.55))]" />
    </div>
</template>
