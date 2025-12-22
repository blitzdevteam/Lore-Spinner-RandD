<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Swiper } from 'swiper/vue';

defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="container flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <h6 class="text-xl">{{ title }}</h6>
            <div v-if="$slots.action">
                <slot name="action"></slot>
            </div>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 w-50 bg-gradient-to-r from-background to-transparent z-10"></div>
            <Swiper
                slidesPerView="auto"
                :freeMode="true"
                :pagination="{ clickable: true }"
            >
                <slot name="slides"></slot>
            </Swiper>
            <div class="absolute inset-y-0 end-0 w-50 bg-gradient-to-l from-background to-transparent z-10"></div>
        </div>
    </div>
</template>

<style>
.swiper {
    width: 100%;
    height: 100%;
}
</style>
