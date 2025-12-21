<script setup lang="ts">
import HomeLayout from '@/layouts/HomeLayout.vue';
import BannerImage from '@/assets/banner.png';
import BaseLogo from '@/components/BaseLogo.vue';
import BaseSlider from '@/components/BaseSlider.vue';
import { SwiperSlide } from 'swiper/vue';
import { WriterInterface } from '@/types';
import { Link } from '@inertiajs/vue3';
import BaseStoryCard from '@/components/BaseStoryCard.vue';

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
            <BaseSlider title="Top Writers">
                <template #action>
                    <Link href="#" class="text-primary">View All (36)</Link>
                </template>
                <template #slides>
                    <SwiperSlide
                        v-for="writer in [...writers, ...writers]"
                        :key="`top-creator-${writer.id}`"
                        class="!w-auto px-3"
                    >
                        <div class="flex flex-col gap-3 items-center w-24">
                            <img :src="writer.avatar" alt="" class="rounded-full" />
                            <p class="text-lg">{{ writer.first_name }}</p>
                        </div>
                    </SwiperSlide>
                </template>
            </BaseSlider>
        </div>
        <div class="flex flex-col gap-12">
            <BaseSlider title="Top Picks">
                <template #action>
                    <Link href="#" class="text-primary">View All (24)</Link>
                </template>
                <template #slides>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                </template>
            </BaseSlider>
            <BaseSlider title="Top Romance Picks">
                <template #action>
                    <Link href="#" class="text-primary">View All (24)</Link>
                </template>
                <template #slides>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                </template>
            </BaseSlider>
            <BaseSlider title="Top Fiction Picks">
                <template #action>
                    <Link href="#" class="text-primary">View All (24)</Link>
                </template>
                <template #slides>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                </template>
            </BaseSlider>
            <BaseSlider title="Top Mystery Picks">
                <template #action>
                    <Link href="#" class="text-primary">View All (24)</Link>
                </template>
                <template #slides>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                    <SwiperSlide class="!w-auto">
                        <div class="px-3 py-4">
                            <BaseStoryCard class="w-112" />
                        </div>
                    </SwiperSlide>
                </template>
            </BaseSlider>
        </div>
    </HomeLayout>
</template>

<style scoped></style>
