<script setup lang="ts">
import { LucideChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const isOpen = ref<boolean>(false);

const componentClass = computed(() => {
    const activeClass = isOpen.value
        ? 'outline-4 outline-primary-200/20 border-primary-500'
        : 'outline-2 outline-transparent hover:outline-primary-200/20 border-gray-700 hover:border-primary-600';

    return `w-full flex flex-col rounded-xl bg-gray-800 border transition-all ${activeClass}`;
});
</script>

<template>
    <div :class="componentClass">
        <div class="flex min-h-18 items-center justify-between px-8 py-4">
            <h3 class="text-xl">
                <slot name="question"></slot>
            </h3>
            <button class="grid size-10 cursor-pointer place-items-center rounded-full hover:bg-gray-700" @click="isOpen = !isOpen">
                <LucideChevronDown :class="`size-6 text-primary-400 transition ${isOpen ? 'rotate-180' : ''}`" />
            </button>
        </div>
        <div class="px-8 pb-4" v-if="isOpen">
            <p class="text-xl font-light text-gray-200"><slot name="answer"></slot></p>
        </div>
    </div>
</template>

<style scoped></style>
