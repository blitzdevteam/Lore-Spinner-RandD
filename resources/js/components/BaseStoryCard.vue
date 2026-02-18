<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { StoryInterface } from '@/types';
import { show } from '@/wayfinder/routes/stories';
import { Link } from '@inertiajs/vue3';
import { LucideBookmark, LucideLayers2, LucideMessageCircleMore, LucidePlay, LucideStar } from 'lucide-vue-next';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        story: StoryInterface;
        type?: 'column' | 'row';
    }>(),
    {
        type: 'row',
    },
);

const isColumn = computed(() => props.type === 'column');
const isRow = computed(() => props.type === 'row');

const getComponent = computed(() => (isColumn.value ? 'div' : Link));
</script>

<template>
    <component
        :is="getComponent"
        :class="{
            'h-full flex-col': isColumn,
            'h-52 flex-row hover:outline-primary-200/20': isRow,
        }"
        class="flex gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500"
        :href="isColumn ? undefined : show(story.slug).url"
    >
        <div :class="{ 'overflow-hidden': isRow }" class="relative rounded-xl">
            <img
                :src="story.cover"
                alt=""
                :class="{
                    'h-48 w-48 object-cover object-center': isRow,
                    'w-full': isColumn,
                }"
                class="rounded-xl"
            />
            <div class="absolute start-3 top-3">
                <BaseButton severity="glass" :icon-only="true">
                    <LucideBookmark class="size-6 text-secondary-200" :stroke-width="1.5" />
                </BaseButton>
            </div>
        </div>
        <div class="flex h-full flex-1 flex-col" :class="{ 'gap-1.5': isRow, 'gap-3': isColumn }">
            <div class="flex items-start justify-between">
                <div :class="['flex w-full', isColumn ? 'flex-row items-center justify-between' : 'flex-col']">
                    <h3 class="text-lg font-semibold">{{ story.title }}</h3>
                    <p class="text-sm text-primary-300" v-if="story.creator">{{ story.creator.full_name }}</p>
                </div>
            </div>
            <div v-if="isColumn" class="grid grid-cols-3 gap-3">
                <div class="flex flex-col items-center justify-center gap-1 rounded-md border border-gray-600 px-4 py-2">
                    <p class="text-gray-300 uppercase">Chapters</p>
                    <span class="text-center font-semibold text-white">{{ story.chapters_count }}</span>
                </div>
                <div class="flex flex-col items-center justify-center gap-1 rounded-md border border-gray-600 px-4 py-2">
                    <p class="text-gray-300 uppercase">Rating</p>
                    <span class="text-center font-semibold text-white">{{ story.rating.label }}</span>
                </div>
                <div class="flex flex-col items-center justify-center gap-1 rounded-md border border-gray-600 px-4 py-2">
                    <p class="text-gray-300 uppercase">Status</p>
                    <span class="text-center font-semibold text-white">{{ story.status.label }}</span>
                </div>
            </div>
            <p class="line-clamp-3 font-light text-gray-200">{{ story.teaser }}</p>
            <div v-if="isColumn" class="grid grid-cols-3 divide-x divide-gray-600">
                <div class="flex flex-col items-center justify-center gap-1.5 px-4">
                    <p class="text-sm text-gray-300">Played</p>
                    <div class="flex items-center gap-1.5">
                        <LucidePlay class="size-4 text-primary" />
                        <span class="font-semibold text-white">110K</span>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-1.5 px-4">
                    <p class="text-sm text-gray-300">Comments</p>
                    <div class="flex items-center gap-1.5">
                        <LucideMessageCircleMore class="size-4 text-primary" />
                        <span class="font-semibold text-white">{{ story.comments_count }}</span>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-1.5 px-4">
                    <p class="text-sm text-gray-300">Category</p>
                    <div class="flex items-center gap-1.5">
                        <LucideLayers2 class="size-4 text-primary" />
                        <span class="font-semibold text-white">{{ story.category?.title }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-auto">
                <template v-if="isRow">
                    <div class="flex items-center gap-1.5">
                        <div class="flex items-center gap-1 rounded-xl bg-white/5 px-1.5 py-1 text-secondary-300">
                            <LucideStar class="size-3.5 fill-current" />
                            <span class="mt-0.5 text-xs">4.5</span>
                        </div>
                        <div class="flex items-center gap-1 rounded-xl bg-white/5 px-1.5 py-1 text-gray-50">
                            <LucidePlay class="size-3.5 fill-current" />
                            <span class="mt-0.5 text-xs">4.5</span>
                        </div>
                    </div>
                </template>
                <template v-if="isColumn">
                    <BaseButton class="w-full" severity="primary" type="internal-link" :href="show(story.slug).url"> View More </BaseButton>
                </template>
            </div>
        </div>
    </component>
</template>

<style scoped></style>
