<script setup lang="ts">
import HomeLayout from '@/layouts/HomeLayout.vue';
import BannerImage from '@/assets/banner.png';
import BaseLogo from '@/components/BaseLogo.vue';
import BaseSlider from '@/components/BaseSlider.vue';
import { SwiperSlide } from 'swiper/vue';
import { WriterInterface } from '@/types';

withDefaults(defineProps<{
    writers?: WriterInterface[];
}>(), {
    writers: () => []
});
</script>

<template>
    <HomeLayout>
        <div
            class="h-108 grid place-items-center bg-cover"
            :style="{background: `url(${BannerImage}) center center no-repeat`, backgroundSize: 'cover'}"
        >
            <div class="container">
                <div class="flex flex-col gap-4 -ms-20 w-86 items-center">
                    <BaseLogo class="w-full" fill="white" />
                    <h3 class="font-gill-sans text-2xl font-light text-primary">Stories That Live Through You</h3>
                </div>
            </div>
        </div>
        <div class="py-24">
            <BaseSlider title="Top Writers" link-to-all="#">
                <SwiperSlide v-for="writer in [...writers, ...writers]" :key="`top-creator-${writer.id}`" class="!w-28">
                    <div class="flex flex-col gap-3 items-center px-3">
                        <img :src="writer.avatar" alt="" class="rounded-full" />
                        <p class="text-lg">{{ writer.first_name }}</p>
                    </div>
                </SwiperSlide>
            </BaseSlider>
        </div>
    </HomeLayout>
</template>

<style scoped></style>
