<script setup lang="ts"></script>

<template>
    <div class="bg-gradient-overlay fixed inset-0 pointer-events-none">
        <div class="absolute top-0 bottom-0 left-0 w-1/4 bg-gradient-to-r from-primary-400/10 to-transparent"></div>
        <div class="absolute top-0 right-0 bottom-0 w-1/4 bg-gradient-to-l from-primary-400/10 to-transparent"></div>
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
