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
    will-change: transform, opacity;
}

.orb::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    opacity: 0.035;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 256px 256px;
}

.orb-1 {
    width: 900px;
    height: 900px;
    top: -20%;
    left: -15%;
    background: radial-gradient(
        circle at center,
        rgba(9, 195, 174, 0.12) 0%,
        rgba(9, 195, 174, 0.08) 15%,
        rgba(9, 195, 174, 0.04) 35%,
        rgba(9, 195, 174, 0.015) 55%,
        rgba(9, 195, 174, 0) 75%
    );
    animation: drift-1 24s ease-in-out infinite alternate;
}

.orb-2 {
    width: 750px;
    height: 750px;
    bottom: -10%;
    right: -12%;
    background: radial-gradient(
        circle at center,
        rgba(84, 244, 218, 0.10) 0%,
        rgba(84, 244, 218, 0.06) 18%,
        rgba(84, 244, 218, 0.03) 40%,
        rgba(84, 244, 218, 0.01) 60%,
        rgba(84, 244, 218, 0) 80%
    );
    animation: drift-2 30s ease-in-out infinite alternate;
}

.orb-3 {
    width: 600px;
    height: 600px;
    top: 35%;
    left: 25%;
    background: radial-gradient(
        circle at center,
        rgba(9, 195, 174, 0.07) 0%,
        rgba(9, 195, 174, 0.04) 20%,
        rgba(9, 195, 174, 0.018) 45%,
        rgba(9, 195, 174, 0.005) 65%,
        rgba(9, 195, 174, 0) 85%
    );
    animation: drift-3 36s ease-in-out infinite alternate;
}

@keyframes drift-1 {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(80px, 60px) scale(1.06); }
}

@keyframes drift-2 {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(-70px, -50px) scale(1.08); }
}

@keyframes drift-3 {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(50px, -60px) scale(1.05); }
}
</style>
