<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { LucideArrowUp } from 'lucide-vue-next';

</script>

<template>
    <div
        class="relative w-150 h-16 rounded-full bg-[linear-gradient(90deg,var(--color-primary-300)_0%,rgba(0,0,0,1)_25%)] flex items-center px-2 border border-gray-700 overflow-hidden"
    >
        <div class="absolute w-full h-full top-0 right-0 bottom-0 left-0 backdrop-blur-xl"></div>
        <PrimeInputText
            class="relative rounded-full! p-2.5! w-full border-gray-700! focus:border-primary-600! shadow-none! outline-none! bg-gray-800!"
            placeholder="What do you do?"
        />
        <BaseButton
            severity="primary"
            :icon-only="true"
            class="size-8! absolute! end-3.75"
            type="button"
        >
            <LucideArrowUp class="text-gray-900 size-6" />
        </BaseButton>
    </div>
</template>

<style scoped>

</style>
