<script setup lang="ts">
import BannerImage from '@/assets/banner.png';
import BaseLogo from '@/components/BaseLogo.vue';
import BaseSlider from '@/components/BaseSlider.vue';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import FrequentlyAskedQuestion from '@/components/FrequentlyAskedQuestion.vue';
import HomeLayout from '@/layouts/HomeLayout.vue';
import { WriterInterface } from '@/types';
import { Link } from '@inertiajs/vue3';
import { SwiperSlide } from 'swiper/vue';

withDefaults(
    defineProps<{
        writers?: WriterInterface[];
    }>(),
    {
        writers: () => [],
    },
);
</script>

<template>
    <HomeLayout>
        <div
            class="grid h-108 place-items-center bg-cover"
            :style="{ background: `url(${BannerImage}) center center no-repeat`, backgroundSize: 'cover' }"
        >
            <div class="container">
                <div class="-ms-20 flex w-86 flex-col items-center gap-4">
                    <BaseLogo class="w-full" fill="white" />
                    <h3 class="font-gill-sans text-2xl font-light text-primary">Stories That Live Through You</h3>
                </div>
            </div>
        </div>

        <div class="pt-48 pb-16">
            <FrequentlyAskedQuestion />
        </div>
    </HomeLayout>
</template>

<style scoped></style>
