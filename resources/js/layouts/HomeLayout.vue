<script setup lang="ts">
import HomeFooter from '@/components/HomeFooter.vue';
import HomeHeader from '@/components/HomeHeader.vue';
</script>

<template>
    <HomeHeader />
    <slot></slot>
    <HomeFooter />
</template>

<style scoped></style>
