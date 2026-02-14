<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LucideBookmark, LucidePlay, LucideStar } from 'lucide-vue-next';
import BaseButton from '@/components/BaseButton.vue';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        type?: 'column' | 'row';
    }>(),
    {
        type: 'row'
    }
);

const isColumn = computed(() => props.type === 'column');
const isRow = computed(() => props.type === 'row');

const getComponent = computed(() => (isColumn.value ? 'div' : Link));
</script>

<template>
    <component
        :is="getComponent"
        :class="{
            'flex-col h-full': isColumn,
            'flex-row h-52 hover:outline-primary-200/20': isRow,
        }"
        class="flex gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500"
        :href="isColumn ? undefined : '#'"
    >
        <div
            :class="{ 'overflow-hidden': isRow }"
            class="rounded-xl relative"
        >
            <img
                src="@/assets/temp/story.jpg"
                alt=""
                :class="{
                    'h-48 w-48 object-cover object-center': isRow,
                    'w-full': isColumn
                }"
                class="rounded-xl"
            />
            <div class="absolute top-3 start-3">
                <BaseButton severity="glass" :icon-only="true">
                    <LucideBookmark class="size-6 text-secondary-200" :stroke-width="1.5" />
                </BaseButton>
            </div>
        </div>
        <div
            class="flex h-full flex-1 flex-col"
            :class="{ 'gap-1.5': isRow, 'gap-3': isColumn }"
        >
            <div class="flex items-start justify-between">
                <div :class="['flex w-full', isColumn ? 'flex-row items-center justify-between' : 'flex-col']">
                    <h3 class="text-lg font-semibold">Story Title</h3>
                    <p class="text-sm text-primary-300">By Rowan Mist</p>
                </div>
            </div>
            <div v-if="isColumn" class="grid grid-cols-4 gap-3">
                <div
                    class="flex flex-col gap-1 items-center justify-center px-4 py-2 border border-gray-600 rounded-md"
                >
                    <p class="text-gray-300 uppercase">Chapters</p>
                    <span class="text-white font-semibold">6</span>
                </div>

                <div
                    class="flex flex-col gap-1 items-center justify-center px-4 py-2 border border-gray-600 rounded-md"
                >
                    <p class="text-gray-300 uppercase">Rating</p>
                    <span class="text-white font-semibold">Teen</span>
                </div>

                <div
                    class="flex flex-col gap-1 items-center justify-center px-4 py-2 border border-gray-600 rounded-md"
                >
                    <p class="text-gray-300 uppercase">Status</p>
                    <span class="text-white font-semibold">Ongoing</span>
                </div>

                <div
                    class="flex flex-col gap-1 items-center justify-center px-4 py-2 border border-gray-600 rounded-md"
                >
                    <p class="text-gray-300 uppercase">Updated</p>
                    <span class="text-white font-semibold">1 Mo. Ago</span>
                </div>
            </div>
            <p class="font-light text-gray-200 line-clamp-3">
                In a future where memories can be traded, two strangers fall in love while trying to steal each other’s
                past. fall in love while trying to steal each other’s past.
            </p>
            <div v-if="isColumn" class="grid grid-cols-4 divide-x divide-gray-600">
                <div class="flex flex-col gap-1.5 items-center justify-center px-4">
                    <p class="text-gray-300 text-sm">Played</p>
                    <div class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-primary" aria-hidden="true" viewBox="0 0 24 24"></svg>
                        <span class="text-white font-semibold">110K</span>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 items-center justify-center px-4">
                    <p class="text-gray-300 text-sm">Played</p>
                    <div class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-primary" aria-hidden="true" viewBox="0 0 24 24"></svg>
                        <span class="text-white font-semibold">110K</span>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 items-center justify-center px-4">
                    <p class="text-gray-300 text-sm">Played</p>
                    <div class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-primary" aria-hidden="true" viewBox="0 0 24 24"></svg>
                        <span class="text-white font-semibold">110K</span>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 items-center justify-center px-4">
                    <p class="text-gray-300 text-sm">Played</p>
                    <div class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-primary" aria-hidden="true" viewBox="0 0 24 24"></svg>
                        <span class="text-white font-semibold">110K</span>
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
                    <BaseButton class="w-full" severity="primary" type="internal-link" href="#">
                        View More
                    </BaseButton>
                </template>
            </div>
        </div>
    </component>
</template>

<style scoped></style>
