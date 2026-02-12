<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LucideBookmark, LucidePlay, LucideStar } from 'lucide-vue-next';
import BaseButton from '@/components/BaseButton.vue';
</script>

<template>
    <Link
        class="flex gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500 hover:outline-primary-200/20 h-48"
        href="#"
    >
        <div class="overflow-hidden rounded-xl relative">
            <img src="@/assets/temp/story.png" alt="" class="h-full w-48 object-cover object-center" />
            <div class="absolute top-2 start-2">
                <BaseButton severity="glass" :icon-only="true" class="rounded-full">
                    <LucideBookmark class="size-6 text-secondary-200" :stroke-width="1.5" />
                </BaseButton>
            </div>
        </div>
        <div class="flex h-full flex-1 flex-col gap-1.5">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold">Story Title</h3>
                    <p class="text-sm text-primary-300">By Rowan Mist</p>
                </div>
            </div>
            <p class="font-light text-gray-200">
                In a future where memories can be traded, two strangers fall in love while trying to steal each other’s past.
            </p>
            <div class="mt-auto">
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
            </div>
        </div>
    </Link>
</template>

<style scoped></style>
