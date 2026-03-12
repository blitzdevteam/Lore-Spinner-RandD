<script setup lang="ts">
import BannerImage from '@/assets/banner.png';
import BaseContentTitle from '@/components/BaseContentTitle.vue';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import BaseLogo from '@/components/BaseLogo.vue';
import HomeLayout from '@/layouts/HomeLayout.vue';
import { StoryInterface } from '@/types';

withDefaults(
    defineProps<{
        stories?: StoryInterface[];
    }>(),
    {
        stories: () => [],
    },
);
</script>

<template>
    <HomeLayout>
        <div
            class="grid h-64 place-items-center bg-cover md:h-108"
            :style="{ background: `url(${BannerImage}) center center no-repeat`, backgroundSize: 'cover' }"
        >
            <div class="container">
                <div class="mx-auto flex w-56 flex-col items-center gap-3 md:mx-0 md:-ms-20 md:w-86 md:gap-4">
                    <BaseLogo class="w-full" fill="white" />
                    <h3 class="text-center font-gill-sans text-lg font-light text-primary md:text-2xl">Stories That Live Through You</h3>
                </div>
            </div>
        </div>

        <div class="py-10 md:py-18">
            <div class="container">
                <div class="flex flex-col gap-12">
                    <BaseContentTitle title="Library">
                        <template #description>
                            Explore
                            <span class="text-primary">original worlds</span>
                            created by creators and unlocked gradually as you read
                        </template>
                    </BaseContentTitle>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">
                        <BaseStoryCard v-for="story in stories" :key="story.id" :story />
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>

<style scoped></style>
