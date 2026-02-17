<script setup lang="ts">
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';
import BaseButton from '@/components/BaseButton.vue';
import { LucideChevronLeft, LucideCog, LucideFileText } from 'lucide-vue-next';
import GameplayInput from '@/components/GameplayInput.vue';

</script>

<template>
    <div class="h-svh relative">
        <BaseBackgroundGradient />
        <div class="h-svh">
            <div class="h-28 px-24 flex items-center justify-between fixed w-full top-0 left-0 r-0 ">
                <div class="flex-1">
                    <BaseButton severity="glass" :icon-only="true" class="size-12!">
                        <LucideChevronLeft class="text-gray-50 size-8" :stroke-width="1.5" />
                    </BaseButton>
                </div>
                <div class="flex-3 text-center">
                    <div class="flex flex-col gap-1.5">
                        <h1 class="text-3xl uppercase">Red Hallow Opens</h1>
                        <div>
                            <span class="bg-gray-800 rounded-full py-1 px-2 text-sm">Chapter 1</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-end gap-3">
                    <BaseButton severity="glass" :icon-only="true" class="size-12!">
                        <LucideCog class="text-secondary-300" />
                    </BaseButton>
                    <BaseButton severity="glass" :icon-only="true" class="size-12!">
                        <LucideFileText class="text-secondary-300" />
                    </BaseButton>
                </div>
            </div>
            <div class="h-svh">
                <div class="max-w-3xl mx-auto flex flex-col justify-end min-h-svh py-28">
                    <div class="flex flex-col divide-y divide-gray-100/20">
                        <slot />
                    </div>
                </div>
            </div>
            <div class="fixed bottom-0 left-1/2 -translate-x-1/2">
                <div class="h-28 grid place-items-center">
                    <GameplayInput />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
