<script setup lang="ts">
import { LucidePlay, LucideStar, LucideBookmark } from 'lucide-vue-next';

</script>

<template>
    <div class="grid grid-cols-3 gap-3 p-3 rounded-xl bg-gray-800 border border-gray-700">
        <div class="col-span-1">
            <div class="rounded-xl overflow-hidden">
                <img src="@/assets/temp/story.png" alt="" class="w-full h-full object-cover" />
            </div>
        </div>
        <div class="col-span-2">
            <div class="flex flex-col gap-1.5 flex-1 h-full">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <h3 class="text-lg font-semibold">Story Title</h3>
                        <p class="text-sm text-primary-300">By Rowan Mist</p>
                    </div>
                    <button>
                        <LucideBookmark class="size-6 text-gray-300 hover:scale-110 hover:text-primary-500 transition" :stroke-width="1.5" />
                    </button>
                </div>
                <p class="text-gray-200 font-light">
                    In a future where memories can be traded, two strangers fall in love while trying to steal each other’s past.
                </p>
                <div class="mt-auto">
                    <div class="flex items-center gap-1.5">
                        <div class="bg-white/5 py-1 px-1.5 rounded-lg flex items-center gap-1 text-secondary-300">
                            <LucideStar class="size-3.5 fill-current"/>
                            <span class="text-xs mt-0.5">4.5</span>
                        </div>
                        <div class="bg-white/5 py-1 px-1.5 rounded-lg flex items-center gap-1 text-gray-50">
                            <LucidePlay class="size-3.5 fill-current"/>
                            <span class="text-xs mt-0.5">4.5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
