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
                    <BaseContentTitle title="Library">
                        <template #description>
                            Explore
                            <span class="text-primary">original worlds</span>
                            created by creators and unlocked gradually as you read
                        </template>
                    </BaseContentTitle>
                    <div class="grid grid-cols-2 gap-6">
                        <BaseStoryCard v-for="story in stories" :key="story.id" :story />
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>

<style scoped></style>
