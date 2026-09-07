import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from '@/pages/LandingPage.vue';
import SpinWheelPage from '@/pages/SpinWheelPage.vue';
import ScratchCardPage from '@/pages/ScratchCardPage.vue';
import SlotMachinePage from '@/pages/SlotMachinePage.vue';

export const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: LandingPage, meta: { title: 'Twiport Lucky Draw' } },
        { path: '/spin', name: 'spin', component: SpinWheelPage, meta: { title: 'Spin Wheel' } },
        { path: '/scratch', name: 'scratch', component: ScratchCardPage, meta: { title: 'Scratch Card' } },
        { path: '/slots', name: 'slots', component: SlotMachinePage, meta: { title: 'Slot Machine' } },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
    scrollBehavior() {
        return { top: 0 };
    },
});

router.afterEach((to) => {
    const title = typeof to.meta.title === 'string' ? to.meta.title : 'Twiport Lucky Draw';
    document.title = `${title} · Twiport`;
});
