<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { LucidePlay, LucideSparkles } from 'lucide-vue-next';

defineProps<{
    prompt: string;
    response: string;
}>()
</script>

<template>
    <div class="flex flex-col gap-4 py-8">
        <div class="flex flex-col gap-2">
            <p class="py-2 px-3 border-s-2 border-secondary-300 text-secondary-300">{{ prompt }}</p>
            <p class="font-light text-lg">{{ response }}</p>
        </div>
        <div class="flex items-center gap-2">
            <BaseButton severity="primary-glass" :icon-only="true" class="size-12!">
                <LucidePlay fill="white" class="text-white size-5" />
            </BaseButton>
            <BaseButton severity="glass" class="rounded-full! ps-1.5! pe-3! py-1!">
                <div class="flex gap-1.5 items-center">
                    <div class="size-9 rounded-full grid place-items-center relative">
                        <span class="bg-primary-glass-effect w-full h-full absolute top-0 left-0 bottom-0 right-0 rounded-full grid place-items-center"></span>
                        <LucideSparkles fill="white" class="text-white size-5 z-5" />
                    </div>
                    <span class="text-primary">Continue</span>
                </div>
            </BaseButton>
        </div>
    </div>
</template>

<style scoped>
</style>
