<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { StoryInterface } from '@/types';
import {
    LucideBookmark,
    LucideShare2,
    LucideChevronLeft,
    LucideMessageCircleMore,
    LucideLayers2, LucidePlay
} from 'lucide-vue-next';
import Tab from 'primevue/tab';
import TabList from 'primevue/tablist';
import TabPanel from 'primevue/tabpanel';
import TabPanels from 'primevue/tabpanels';
import Tabs from 'primevue/tabs';
import StoryGallery from '@/components/StoryGallery.vue';
import StoryChapterCard from '@/components/StoryChapterCard.vue';
import StoryCommentCard from '@/components/StoryCommentCard.vue';

defineProps<{
    story: StoryInterface;
}>();
</script>

<template>
    <div class="flex min-h-svh">
        <div class="relative h-full flex-1">
            <div class="absolute top-0 right-0 bottom-0 -start-7.5 z-0 h-full w-[115%] blur-xl">
                <img :src="story.cover" alt="" class="object-cover object-center opacity-75" />
            </div>
            <div class="p-12">
                <div class="flex flex-col gap-8">
                    <div class="relative overflow-hidden rounded-3xl aspect-video">
                        <div class="z-10 absolute top-0 right-0 left-0 p-8 w-full">
                            <div class="flex items-center justify-between">
                                <BaseButton :icon-only="true" type="button" severity="glass" class="size-12!">
                                    <LucideChevronLeft class="size-8" :stroke-width="1.5" />
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
                        </div>
                        <div class="grid relative">
                            <div class="absolute bg-linear-to-b from-black/35 to-transparent top-0 right-0 bottom-0 left-0 w-full h-full z-5"></div>
                            <StoryGallery
                                :gallery="[story.cover]"
                            />
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 relative">
                        <div class="flex justify-between items-center">
                            <h3 class="text-3xl font-semibold text-white">{{ story.title }}</h3>
                        </div>
                        <div class="flex items-center gap-12">
                            <div class="flex items-center gap-1.5 text-gray-400">
                                <LucidePlay class="size-6" />
                                <span class="textlg font-semibold">110K</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-400">
                                <LucideMessageCircleMore class="size-6" />
                                <span class="textlg font-semibold">{{ story.comments_count }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-400">
                                <LucideLayers2 class="size-6" />
                                <span class="textlg font-semibold">{{ story.category?.title }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-4">
                        <div class="flex items-center gap-4">
                            <img :src="story.creator?.avatar" alt="" class="size-16 rounded-full">
                            <div class="text-gray-400 text-xl font-semibold">
                                {{ story.creator?.full_name  }}
                            </div>
                        </div>
                        <p class="leading-relaxed text-xl font-light text-gray-100">{{ story.teaser }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky top-0 bottom-0 flex h-svh w-120 border-s border-gray-700 bg-gray-900">
            <Tabs value="details_chapters" class="px-8 flex flex-col gap-8 py-8 w-full overflow-y-scroll" :show-navigators="false" unstyled>
                <TabList pt:tab-list="flex items-center gap-4" pt:content="" pt:active-bar="hidden">
                    <Tab class="flex-1" value="details_chapters" v-slot="slotProps" as-child>
                        <BaseButton
                            @click="slotProps.onClick"
                            class="w-full"
                            :severity="slotProps.active ? 'primary-muted-outline' : 'gray-muted'"
                        >
                            Details / Chapters
                        </BaseButton>
                    </Tab>
                    <Tab class="flex-1" value="comments" v-slot="slotProps" as-child>
                        <BaseButton
                            @click="slotProps.onClick"
                            class="w-full"
                            :severity="slotProps.active ? 'primary-muted-outline' : 'gray-muted'"
                        >
                            Comments
                        </BaseButton>
                    </Tab>
                </TabList>
                <TabPanels class="container">
                    <TabPanel value="details_chapters">
                        <div class="flex flex-col gap-8">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-gray-700 px-4 py-3 bg-gray-800/50">
                                    <p class="text-gray-300 uppercase font-semibold">Chapters</p>
                                    <span class="text-lg text-center  text-white">{{ story.chapters_count }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-gray-700 px-4 py-3 bg-gray-800/50">
                                    <p class="text-gray-300 uppercase font-semibold">Rating</p>
                                    <span class="text-lg text-center  text-white">{{ story.rating.label }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-gray-700 px-4 py-3 bg-gray-800/50">
                                    <p class="text-gray-300 uppercase font-semibold">Status</p>
                                    <span class="text-lg text-center  text-white">{{ story.status.label }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-gray-700 px-4 py-3 bg-gray-800/50">
                                    <p class="text-gray-300 uppercase font-semibold">Updated</p>
                                    <span class="text-lg text-center  text-white">2 Months Ago</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-4">
                                <StoryChapterCard
                                    v-for="(chapter, index) in story.chapters"
                                    :key="chapter.id"
                                    :chapter
                                    :is-open="index === 0"
                                />
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel value="comments">
                        <div class="flex flex-col gap-4">
                            <StoryCommentCard />
                            <StoryCommentCard />
                            <StoryCommentCard />
                            <StoryCommentCard />
                        </div>
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </div>
    </div>
</template>

<style scoped></style>
