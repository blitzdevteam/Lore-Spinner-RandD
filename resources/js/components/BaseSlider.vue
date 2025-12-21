<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Swiper } from 'swiper/vue';
import { FreeMode } from 'swiper/modules';

defineProps<{
    title: string;
    linkToAll: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="container flex items-center justify-between">
            <h6 class="text-xl">{{ title }}</h6>
            <Link :href="linkToAll" class="text-primary">View All (36)</Link>
        </div>
        <Swiper
            slidesPerView="auto"
            :freeMode="true"
            :pagination="{ clickable: true }"
        >
            <slot></slot>
        </Swiper>
    </div>
</template>

<style>
.swiper {
    width: 100%;
    height: 100%;
}
</style>
