<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/pagination';

defineProps<{
    gallery: string[];
}>()
</script>

<template>
    <swiper
        :slides-per-view="1"
        :space-between="16"
        :pagination="true"
        :autoplay="true"
        :loop="true"
        :modules="[Pagination]"
        class="story-gallery-swiper"
    >
        <swiper-slide v-for="(image, index) in gallery" :key="index">
            <img :src="image" alt="" class="w-full h-full object-cover object-center rounded-3xl" />
        </swiper-slide>
    </swiper>
</template>

<style>
@reference "tailwindcss";

.story-gallery-swiper {
    @apply aspect-video w-full overflow-hidden;

    .swiper-slide {
        @apply w-full;
    }

    .swiper-pagination {
        @apply h-2 p-0 m-0 absolute bottom-8 left-1/2 flex items-center gap-2 justify-center -translate-x-1/2;

        .swiper-pagination-bullet {
            @apply w-10 h-1.5 rounded-lg bg-white opacity-15 m-0 p-0;

            &.swiper-pagination-bullet-active {
                @apply bg-white opacity-100;
            }
        }
    }
}
</style>
