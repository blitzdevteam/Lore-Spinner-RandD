<script setup lang="ts">
import { ChapterInterface } from '@/types';
import { LucideChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';

const isOpen = ref<boolean>(false);

defineProps<{
    chapter: ChapterInterface
}>();
</script>

<template>
    <div
        @click="isOpen = ! isOpen"
        class="rounded-xl border border-gray-700 p-2 bg-gray-800/50 cursor-pointer"
    >
        <div
            :class="{
                'items-start': isOpen,
                'items-center': ! isOpen
            }"
            class="flex gap-2.5"
        >
            <div
                :class="{
                    'h-36': isOpen,
                    'h-20': ! isOpen
                }"
                class="w-36 overflow-hidden border border-gray-700 rounded-lg relative"
            >
                <img
                    src="@/assets/temp/chapter.png"
                    alt=""
                    class="min-w-36 min-h-36 w-full h-full object-center object-cover"
                >
                <div class="absolute top-2 left-2 right-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 bg-muted-glass-effect rounded-full text-secondary-300 text-sm">
                            Active
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <h3 class="flex-1 line-clamp-1 text-lg text-secondary-300">{{ chapter.title }}</h3>
                    <button class="size-6 grid place-items-center mx-2.5">
                        <LucideChevronDown
                            :class="{
                                'rotate-180': isOpen
                            }"
                            :stroke-width="1.5"
                        />
                    </button>
                </div>
                <template v-if="isOpen">
                    <p class="text-sm font-light line-clamp-3">{{ chapter.teaser }}</p>
                    <div class="flex items-center justify-between mt-auto">
                        <div
                            class="rounded-lg border text-sm text-gray-400 border-gray-700 py-1 px-2 flex items-center gap-1.5">
                            <span>Events</span>
                            <span>{{ chapter.events_count }}</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
