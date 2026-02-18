<script setup lang="ts">
import BannerImage from '@/assets/banner.png';
import BaseButton from '@/components/BaseButton.vue';
import BaseContentTitle from '@/components/BaseContentTitle.vue';
import BaseCreatorCard from '@/components/BaseCreatorCard.vue';
import BaseLogo from '@/components/BaseLogo.vue';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import CommunitySignup from '@/components/CommunitySignup.vue';
import FrequentlyAskedQuestion from '@/components/FrequentlyAskedQuestion.vue';
import HomeLayout from '@/layouts/HomeLayout.vue';
import { CreatorInterface, StoryInterface } from '@/types';

withDefaults(
    defineProps<{
        creators?: CreatorInterface[];
        stories?: StoryInterface[];
    }>(),
    {
        creators: () => [],
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
                    <BaseContentTitle title="Creators">
                        <template #description>
                            Meet the minds behind the worlds you love and explore the worlds they are actively
                            <span class="text-primary">bringing to life</span>
                        </template>
                    </BaseContentTitle>
                    <div class="flex flex-col gap-6">
                        <div class="grid grid-cols-3 gap-6">
                            <BaseCreatorCard v-for="creator in creators" :key="creator.username" :creator />
                        </div>
                        <div class="mx-auto">
                            <BaseButton class="w-64 text-lg" severity="transparent"> View All ({{ creators.length }}) </BaseButton>
                        </div>
                    </div>
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
                            created by creators and unlocked gradually as you read
                        </template>
                    </BaseContentTitle>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <div class="flex flex-col gap-6">
                                <BaseStoryCard v-for="story in [...stories, ...stories, ...stories]" :story />
                            </div>
                        </div>
                        <div class="col-span-1">
                            <BaseStoryCard :story="stories[0]" type="column" />
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
