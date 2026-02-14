<script setup lang="ts">
import HomeHeader from '@/components/HomeHeader.vue';
import HomeFooter from '@/components/HomeFooter.vue';
</script>

<template>
    <HomeHeader />
    <slot></slot>
    <HomeFooter />
</template>

<style scoped></style>
