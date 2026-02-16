<script setup lang="ts">
import { StoryInterface } from '@/types';
import { LucideBookmark, LucideX, LucideShare2 } from 'lucide-vue-next';
import BaseButton from '@/components/BaseButton.vue';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';

defineProps<{
    story: StoryInterface
}>();
</script>

<template>
    <div class="h-svh flex">
        <div class="flex-1 h-full relative overflow-hidden">
            <template v-if="false">
                <div class="z-0 absolute top-0 right-0 left-0 bottom-0 h-full w-full blur-2xl">
                    <img :src="story.cover" alt="" class="w-[200%] object-cover object-center opacity-50">
                </div>
                <div class="p-12">
                    <div class="flex flex-col gap-12">
                        <div class="flex items-center justify-between">
                            <BaseButton :icon-only="true" type="button" severity="glass" class="size-12!">
                                <LucideX class="size-8" :stroke-width="1.5" />
                            </BaseButton>
                            <div class="flex items-center gap-3">
                                <BaseButton severity="glass" :icon-only="true" class="size-12!">
                                    <LucideBookmark class="size-6 text-secondary-300" :stroke-width="1.5" />
                                </BaseButton>
                                <BaseButton severity="glass" :icon-only="true" class="size-12!">
                                    <LucideShare2 class="size-6 text-secondary-300" :stroke-width="1.5" />
                                </BaseButton>
                            </div>
                        </div>
                        <div class="grid place-items-center">
                            <img :src="story.cover" alt="" class="rounded-3xl">
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="w-lg h-full border-s border-gray-700">
            <div class="py-8">
                <Tabs value="details" class="px-8" :show-navigators="false" unstyled>
                    <TabList
                        pt:tab-list="flex items-center gap-4"
                        pt:content=""
                        pt:active-bar="hidden"
                    >
                        <Tab class="flex-1" value="details" v-slot="slotProps" as-child>
                            <BaseButton
                                @click="slotProps.onClick"
                                class="w-full"
                                :severity="slotProps.active ? 'secondary-muted-outline' : 'gray-muted'"
                            >
                                Details
                            </BaseButton>
                        </Tab>
                        <Tab class="flex-1" value="comments" v-slot="slotProps" as-child>
                            <BaseButton
                                @click="slotProps.onClick"
                                class="w-full"
                                :severity="slotProps.active ? 'secondary-muted-outline' : 'gray-muted'"
                            >
                                Comments
                            </BaseButton>
                        </Tab>
                    </TabList>
                    <TabPanels class="container">
                        <TabPanel value="stories">

                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
