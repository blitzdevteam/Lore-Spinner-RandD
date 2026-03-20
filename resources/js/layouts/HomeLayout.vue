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
    overflow: hidden;
}

.orb {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: -1;
    will-change: transform;
}

.orb::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    opacity: 0.03;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 256px 256px;
    mask-image: radial-gradient(ellipse at center, black 0%, black 30%, transparent 65%);
    -webkit-mask-image: radial-gradient(ellipse at center, black 0%, black 30%, transparent 65%);
}

.orb-1 {
    width: 140vw;
    height: 140vh;
    top: -50%;
    left: -30%;
    background: radial-gradient(
        ellipse at center,
        rgba(9, 195, 174, 0.06) 0%,
        rgba(9, 195, 174, 0.03) 20%,
        rgba(9, 195, 174, 0.01) 40%,
        rgba(9, 195, 174, 0) 60%
    );
    animation: drift-1 30s ease-in-out infinite alternate;
}

.orb-2 {
    width: 120vw;
    height: 120vh;
    bottom: -50%;
    right: -30%;
    background: radial-gradient(
        ellipse at center,
        rgba(84, 244, 218, 0.05) 0%,
        rgba(84, 244, 218, 0.025) 20%,
        rgba(84, 244, 218, 0.008) 45%,
        rgba(84, 244, 218, 0) 65%
    );
    animation: drift-2 38s ease-in-out infinite alternate;
}

.orb-3 {
    width: 100vw;
    height: 100vh;
    top: 10%;
    left: 5%;
    background: radial-gradient(
        ellipse at center,
        rgba(9, 195, 174, 0.035) 0%,
        rgba(9, 195, 174, 0.015) 25%,
        rgba(9, 195, 174, 0.004) 50%,
        rgba(9, 195, 174, 0) 70%
    );
    animation: drift-3 44s ease-in-out infinite alternate;
}

@keyframes drift-1 {
    0%   { transform: translate(0, 0); }
    100% { transform: translate(5vw, 4vh); }
}

@keyframes drift-2 {
    0%   { transform: translate(0, 0); }
    100% { transform: translate(-4vw, -3vh); }
}

@keyframes drift-3 {
    0%   { transform: translate(0, 0); }
    100% { transform: translate(3vw, -4vh); }
}
</style>
