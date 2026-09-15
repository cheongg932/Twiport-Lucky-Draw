<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { animate } from 'motion';
import { motion } from 'motion-v';
import { createLenis } from '@/composables/useLenis';
import ProductVisual from '@/components/ProductVisual.vue';
import { ArrowRight, Gift, RotateCw, Ticket } from '@lucide/vue';

gsap.registerPlugin(ScrollTrigger);

const hero = ref<HTMLElement | null>(null);
const phone = ref<HTMLElement | null>(null);
let teardown: (() => void) | null = null;

const games = [
    {
        to: '/spin',
        kicker: 'Game 01',
        title: 'Spin Wheel',
        copy: 'A golden ring of iPhones, AirPods and MacBooks. One flick. One fate.',
        icon: RotateCw,
    },
    {
        to: '/scratch',
        kicker: 'Game 02',
        title: 'Scratch Card',
        copy: 'Peel the holographic foil and see if titanium is hiding underneath.',
        icon: Ticket,
    },
    {
        to: '/slots',
        kicker: 'Game 03',
        title: 'Slot Machine',
        copy: 'Three reels. One jackpot line. Match an iPhone trio and take it home.',
        icon: Gift,
    },
];

const prizes = [
    { kind: 'iphone' as const, name: 'iPhone 16 Pro', value: 'RM 5,499' },
    { kind: 'macbook' as const, name: 'MacBook Air', value: 'RM 4,799' },
    { kind: 'ipad' as const, name: 'iPad Pro', value: 'RM 4,199' },
    { kind: 'watch' as const, name: 'Watch Ultra', value: 'RM 3,499' },
    { kind: 'airpods' as const, name: 'AirPods Pro', value: 'RM 999' },
    { kind: 'voucher' as const, name: 'Gift Voucher', value: 'RM 200' },
];

onMounted(() => {
    const { destroy } = createLenis();

    const ctx = gsap.context(() => {
        gsap.from('.hero-kicker', { y: 24, opacity: 0, duration: 0.8, ease: 'power3.out' });
        gsap.from('.hero-line', {
            yPercent: 110,
            duration: 1.1,
            ease: 'power4.out',
            stagger: 0.08,
            delay: 0.1,
        });
        gsap.from('.hero-copy', { y: 20, opacity: 0, duration: 0.9, delay: 0.45, ease: 'power3.out' });
        gsap.from('.hero-cta', { y: 16, opacity: 0, duration: 0.8, delay: 0.6, stagger: 0.1 });

        if (phone.value) {
            gsap.to(phone.value, {
                y: -18,
                rotateY: 8,
                rotateX: -4,
                duration: 3.4,
                yoyo: true,
                repeat: -1,
                ease: 'sine.inOut',
            });
        }

        gsap.utils.toArray<HTMLElement>('.reveal').forEach((el) => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: 'top 84%' },
                y: 40,
                opacity: 0,
                duration: 0.9,
                ease: 'power3.out',
            });
        });
    }, hero.value ?? undefined);

    const cards = Array.from(document.querySelectorAll<HTMLElement>('.game-card'));
    const onMove = (event: PointerEvent) => {
        const card = event.currentTarget as HTMLElement;
        const rect = card.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - 0.5;
        const y = (event.clientY - rect.top) / rect.height - 0.5;
        animate(card, { rotateY: x * 10, rotateX: -y * 8 }, { duration: 0.4 });
    };
    const onLeave = (event: PointerEvent) => {
        animate(event.currentTarget as HTMLElement, { rotateY: 0, rotateX: 0 }, { duration: 0.5 });
    };

    cards.forEach((card) => {
        card.addEventListener('pointermove', onMove);
        card.addEventListener('pointerleave', onLeave);
    });

    teardown = () => {
        cards.forEach((card) => {
            card.removeEventListener('pointermove', onMove);
            card.removeEventListener('pointerleave', onLeave);
        });
        ctx.revert();
        destroy();
        ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
    };
});

onUnmounted(() => {
    teardown?.();
});
</script>

<template>
    <div ref="hero" class="overflow-x-hidden pb-20">
        <section class="relative mx-auto grid min-h-[calc(100vh-6rem)] max-w-6xl items-center gap-12 px-5 pb-10 pt-10 sm:px-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <p class="hero-kicker text-xs tracking-[0.45em] text-[#f6d889]">LUCKY DRAW</p>
                <h1 class="mt-5 font-display text-5xl font-extrabold leading-[0.92] sm:text-7xl">
                    <span class="block overflow-hidden"><span class="hero-line inline-block">Win the glow.</span></span>
                    <span class="block overflow-hidden"><span class="hero-line gold-text inline-block">Take home</span></span>
                    <span class="block overflow-hidden"><span class="hero-line inline-block">an iPhone.</span></span>
                </h1>
                <p class="hero-copy mt-6 max-w-lg text-lg text-white/70">
                    A cinematic night market of prizes — iPhone 16 Pro, MacBook Air, Watch Ultra, AirPods Pro.
                    Three games. Infinite swagger. One lucky draw.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <RouterLink to="/spin" class="hero-cta rounded-full bg-gradient-to-r from-[#f6d889] to-[#ffd36b] px-6 py-3 text-sm font-bold text-[#3a2a08]">
                        Start with the wheel
                    </RouterLink>
                    <a href="#games" class="hero-cta rounded-full border border-white/15 px-6 py-3 text-sm text-white/80">
                        See all games
                    </a>
                </div>
            </div>
            <div class="relative mx-auto" style="perspective: 1200px">
                <div class="absolute inset-x-10 top-10 h-64 rounded-full bg-[radial-gradient(circle,rgba(246,216,137,0.28),transparent_70%)] blur-2xl" />
                <div ref="phone" class="relative" style="transform-style: preserve-3d">
                    <ProductVisual kind="iphone" />
                </div>
                <div class="float-slow absolute -left-6 top-16 hidden sm:block">
                    <ProductVisual kind="airpods" size="sm" />
                </div>
                <div class="float-slow absolute -right-4 bottom-8 hidden delay-200 sm:block" style="animation-delay: 1.2s">
                    <ProductVisual kind="watch" size="sm" />
                </div>
            </div>
        </section>

        <div class="relative overflow-hidden border-y border-white/10 bg-white/5 py-4">
            <div class="marquee-track flex w-max gap-10 whitespace-nowrap px-6 text-sm tracking-[0.25em] text-white/70">
                <span v-for="n in 2" :key="n" class="flex gap-10">
                    <span>IPHONE 16 PRO</span>
                    <span class="text-[#f6d889]">★</span>
                    <span>MACBOOK AIR</span>
                    <span class="text-[#3de0ff]">★</span>
                    <span>IPAD PRO</span>
                    <span class="text-[#ff3cac]">★</span>
                    <span>WATCH ULTRA</span>
                    <span class="text-[#f6d889]">★</span>
                    <span>AIRPODS PRO</span>
                    <span class="text-[#3de0ff]">★</span>
                    <span>GIFT VOUCHER</span>
                    <span class="text-[#ff3cac]">★</span>
                </span>
            </div>
        </div>

        <section id="games" class="mx-auto mt-20 max-w-6xl px-5 sm:px-8">
            <div class="reveal max-w-2xl">
                <p class="text-xs tracking-[0.35em] text-[#3de0ff]">CHOOSE YOUR GAME</p>
                <h2 class="mt-3 font-display text-4xl font-bold sm:text-5xl">Three ways to get lucky.</h2>
                <p class="mt-3 text-white/65">UI-first, animation-heavy, and tuned for that “did I just win an iPhone?” heartbeat.</p>
            </div>
            <div class="mt-10 grid gap-5 lg:grid-cols-3" style="perspective: 1400px">
                <RouterLink
                    v-for="game in games"
                    :key="game.to"
                    :to="game.to"
                    class="game-card glass-panel group rounded-[1.8rem] p-6"
                    style="transform-style: preserve-3d"
                >
                    <component :is="game.icon" class="text-[#f6d889]" :size="28" />
                    <p class="mt-8 text-xs tracking-[0.3em] text-white/45">{{ game.kicker }}</p>
                    <h3 class="mt-2 font-display text-2xl font-bold">{{ game.title }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-white/65">{{ game.copy }}</p>
                    <span class="mt-8 inline-flex items-center gap-2 text-sm text-[#f6d889]">
                        Enter game <ArrowRight :size="16" />
                    </span>
                </RouterLink>
            </div>
        </section>

        <section class="mx-auto mt-24 max-w-6xl px-5 sm:px-8">
            <div class="reveal mb-10 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs tracking-[0.35em] text-[#ff3cac]">PRIZE WALL</p>
                    <h2 class="mt-3 font-display text-4xl font-bold">Could be yours tonight.</h2>
                </div>
                <p class="hidden max-w-sm text-right text-sm text-white/50 md:block">
                    Real product-shaped rewards, not abstract points. iPhones lead the board.
                </p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <motion.article
                    v-for="(prize, index) in prizes"
                    :key="prize.name"
                    class="glass-panel rounded-[1.8rem] p-6"
                    :initial="{ opacity: 0, y: 24 }"
                    :while-in-view="{ opacity: 1, y: 0, transition: { delay: index * 0.06 } }"
                    :in-view-options="{ once: true }"
                >
                    <div class="flex h-[150px] items-center justify-center overflow-hidden">
                        <ProductVisual :kind="prize.kind" size="md" />
                    </div>
                    <h3 class="mt-2 font-display text-xl font-bold">{{ prize.name }}</h3>
                    <p class="text-[#f6d889]">{{ prize.value }}</p>
                </motion.article>
            </div>
        </section>

        <section class="mx-auto mt-24 max-w-6xl px-5 sm:px-8">
            <div class="reveal overflow-hidden rounded-[2.2rem] border border-white/10 bg-[linear-gradient(135deg,rgba(255,60,172,0.18),rgba(61,224,255,0.12)_45%,rgba(246,216,137,0.16))] px-8 py-14 text-center">
                <p class="font-serif text-2xl italic text-white/90 sm:text-3xl">“One scratch. One spin. One drop.”</p>
                <p class="mt-4 text-white/65">Lucky Draw — built on PHP 8.2, Laravel 12, Vue 3 and a little chaos.</p>
                <RouterLink to="/slots" class="mt-8 inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-[#05060b]">
                    Pull the slot lever
                </RouterLink>
            </div>
        </section>
    </div>
</template>
