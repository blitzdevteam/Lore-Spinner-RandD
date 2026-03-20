<script setup lang="ts">
import HomeFooter from '@/components/HomeFooter.vue';
import HomeHeader from '@/components/HomeHeader.vue';
</script>

<template>
    <div class="home-layout-root">
        <!-- Ambient gradient orbs -->
        <div class="orb orb-1" aria-hidden="true" />
        <div class="orb orb-2" aria-hidden="true" />
        <div class="orb orb-3" aria-hidden="true" />

        <HomeHeader />
        <slot></slot>
        <HomeFooter />
    </div>
</template>

<style scoped>
.home-layout-root {
    position: relative;
    isolation: isolate;
    overflow-x: hidden;
}

/* Shared orb base */
.orb {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: -1;
    filter: blur(120px);
    will-change: transform;
    opacity: 0;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
    animation-direction: alternate;
}

/* Orb 1 — top-left, primary teal */
.orb-1 {
    width: 680px;
    height: 680px;
    top: -160px;
    left: -200px;
    background: radial-gradient(circle, #09c3ae 0%, transparent 70%);
    opacity: 0.09;
    animation: drift-1 18s ease-in-out infinite alternate;
}

/* Orb 2 — bottom-right, slightly warmer teal */
.orb-2 {
    width: 520px;
    height: 520px;
    bottom: 5%;
    right: -180px;
    background: radial-gradient(circle, #54f4da 0%, transparent 70%);
    opacity: 0.07;
    animation: drift-2 22s ease-in-out infinite alternate;
}

/* Orb 3 — center-ish, very subtle */
.orb-3 {
    width: 400px;
    height: 400px;
    top: 40%;
    left: 30%;
    background: radial-gradient(circle, #09c3ae 0%, transparent 70%);
    opacity: 0.045;
    animation: drift-3 28s ease-in-out infinite alternate;
}

@keyframes drift-1 {
    0%   { transform: translate(0, 0) scale(1); }
    33%  { transform: translate(60px, 80px) scale(1.08); }
    66%  { transform: translate(20px, 140px) scale(0.96); }
    100% { transform: translate(80px, 40px) scale(1.04); }
}

@keyframes drift-2 {
    0%   { transform: translate(0, 0) scale(1); }
    40%  { transform: translate(-70px, -60px) scale(1.1); }
    70%  { transform: translate(-30px, -120px) scale(0.94); }
    100% { transform: translate(-80px, -30px) scale(1.06); }
}

@keyframes drift-3 {
    0%   { transform: translate(0, 0) scale(1); }
    50%  { transform: translate(50px, -80px) scale(1.12); }
    100% { transform: translate(-40px, 60px) scale(0.92); }
}
</style>
