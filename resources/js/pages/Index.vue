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
import CommunitySignup from '@/components/CommunitySignup.vue';
import BaseContentTitle from '@/components/BaseContentTitle.vue';

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

        <div class="py-18">
            <div class="container">
                <div class="flex flex-col gap-12">
                    <BaseContentTitle title="Stories">
                        <template #description>
                            Explore
                            <span class="text-primary">original worlds</span>
                            created by writers and unlocked gradually as you read
                        </template>
                    </BaseContentTitle>
                    <div class="grid grid-cols-2 gap-8">
                        <div class="col-span-1">
                            asd
                        </div>
                        <div class="col-span-1">
                            <BaseStoryCard />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-18">
            <div class="container">
                <CommunitySignup />
            </div>
        </div>

        <div class="pt-18 pb-16">
            <div class="container">
                <FrequentlyAskedQuestion />
            </div>
        </div>
    </HomeLayout>
</template>

<style scoped></style>
