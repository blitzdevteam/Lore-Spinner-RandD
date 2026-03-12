<script setup lang="ts"></script>

<template>
    <div class="bg-gradient-overlay fixed inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,var(--color-primary-400)_0%,transparent_50%)] opacity-10"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_right,var(--color-primary-400)_0%,transparent_50%)] opacity-10"></div>
    </div>
</template>

<style scoped>
.bg-gradient-overlay::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0.035;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 256px 256px;
}
</style>
