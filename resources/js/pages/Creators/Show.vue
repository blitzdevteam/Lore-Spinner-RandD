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
        <div class="py-16">

        </div>
    </HomeLayout>
</template>

<style>
.p-tablist-tab-list {
    display: flex;
    justify-content: center;
}
</style>
