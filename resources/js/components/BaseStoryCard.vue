<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LucideBookmark, LucidePlay, LucideStar } from 'lucide-vue-next';
</script>

<template>
    <Link
        class="grid grid-cols-3 gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500 hover:outline-primary-200/20"
        href="#"
    >
        <div class="col-span-1">
            <div class="overflow-hidden rounded-xl">
                <img src="@/assets/temp/story.png" alt="" class="h-full w-full object-cover" />
            </div>
        </div>
        <div class="col-span-2">
            <div class="flex h-full flex-1 flex-col gap-1.5">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <h3 class="text-lg font-semibold">Story Title</h3>
                        <p class="text-sm text-primary-300">By Rowan Mist</p>
                    </div>
                    <button>
                        <LucideBookmark class="size-6 text-gray-300 transition hover:scale-110 hover:text-primary-500" :stroke-width="1.5" />
                    </button>
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
        </div>
    </Link>
</template>

<style scoped></style>
