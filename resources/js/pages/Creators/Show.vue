<script setup lang="ts">
import { CreatorInterface } from '@/types';
import HomeLayout from '@/layouts/HomeLayout.vue';
import CreatorHeaderBanner from '@/assets/creator-header-banner.png';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';

defineProps<{
    creator: CreatorInterface
}>()
</script>

<template>
    <HomeLayout>
        <div
            class="h-100 flex items-center"
            :style="{ background: `url(${CreatorHeaderBanner}) center center no-repeat`, backgroundSize: 'cover' }"
        >
            <div class="container">
                <div class="inline-flex flex-col gap-3 items-center">
                    <img :src="creator.avatar" alt="" class="rounded-full size-40">
                    <div class="flex flex-col">
                        <h3 class="text-center text-white text-3xl">{{creator.full_name}}</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-lg text-white font-light">18 Stories</span>
                            <span class="text-lg  w-px h-4 bg-white"></span>
                            <span class="text-lg text-white font-light">200K Reads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Tabs value="stories">
            <TabList
                pt:tab-list="flex justify-center"
                pt:content="bg-gray-800"
            >
                <Tab class="text-lg font-light!" value="stories">Stories</Tab>
                <Tab class="text-lg font-light!" value="about">About</Tab>
                <Tab class="text-lg font-light!" value="activity">Activity</Tab>
            </TabList>
            <TabPanels class="container">
                <TabPanel value="stories" class="py-16 grid grid-cols-2 gap-6">
                    <BaseStoryCard />
                    <BaseStoryCard />
                    <BaseStoryCard />
                    <BaseStoryCard />
                </TabPanel>
                <TabPanel value="about" class="py-16">

                </TabPanel>
                <TabPanel value="activity" class="py-16">

                </TabPanel>
            </TabPanels>
        </Tabs>
    </HomeLayout>
</template>

<style>
</style>
