<script setup lang="ts">
import CreatorHeaderBanner from '@/assets/creator-header-banner.png';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import HomeLayout from '@/layouts/HomeLayout.vue';
import { CreatorInterface } from '@/types';
import Tab from 'primevue/tab';
import TabList from 'primevue/tablist';
import TabPanel from 'primevue/tabpanel';
import TabPanels from 'primevue/tabpanels';
import Tabs from 'primevue/tabs';

defineProps<{
    creator: CreatorInterface;
}>();
</script>

<template>
    <HomeLayout>
        <div class="flex h-100 items-center" :style="{ background: `url(${CreatorHeaderBanner}) center center no-repeat`, backgroundSize: 'cover' }">
            <div class="container">
                <div class="inline-flex flex-col items-center gap-3">
                    <img :src="creator.avatar" alt="" class="size-40 rounded-full object-cover object-[50%_20%]" />
                    <div class="flex flex-col">
                        <h3 class="text-center text-3xl text-white">{{ creator.full_name }}</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-light text-white">18 Stories</span>
                            <span class="h-4 w-px bg-white text-lg"></span>
                            <span class="text-lg font-light text-white">200K Reads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Tabs value="stories">
            <TabList pt:tab-list="flex justify-center border-y!" pt:content="bg-gray-800">
                <Tab class="text-lg font-light!" value="stories">Stories</Tab>
                <Tab class="text-lg font-light!" value="about">About</Tab>
                <Tab class="text-lg font-light!" value="activity">Activity</Tab>
            </TabList>
            <TabPanels class="container">
                <TabPanel value="stories" class="grid grid-cols-1 gap-4 py-10 sm:grid-cols-2 sm:gap-6 sm:py-16">
                    <BaseStoryCard v-for="story in creator.stories ?? []" :key="story.id" :story />
                </TabPanel>
                <TabPanel value="about" class="py-16"> </TabPanel>
                <TabPanel value="activity" class="py-16"> </TabPanel>
            </TabPanels>
        </Tabs>
    </HomeLayout>
</template>

<style></style>
