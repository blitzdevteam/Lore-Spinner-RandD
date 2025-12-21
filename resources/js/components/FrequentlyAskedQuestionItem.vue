<script setup lang="ts">
import { computed, ref } from 'vue';
import { LucideChevronDown } from 'lucide-vue-next';

const isOpen = ref<boolean>(false);

const componentClass = computed(() => {
    const activeClass = isOpen.value ?
        'outline-4 outline-primary-200/20 border-primary-500' :
        'outline-2 outline-transparent hover:outline-primary-200/20 border-gray-700 hover:border-primary-600';

    return `w-full flex flex-col rounded-xl bg-gray-800 border transition-all ${activeClass}`;
});
</script>

<template>
    <div :class="componentClass">
        <div class="flex items-center justify-between px-8 py-4 min-h-18">
            <h3 class="text-xl">
                <slot name="question"></slot>
            </h3>
            <button class="hover:bg-gray-700 size-10 grid place-items-center rounded-full cursor-pointer" @click="isOpen = !isOpen">
                <LucideChevronDown :class="`transition size-6 text-primary-400 ${isOpen ? 'rotate-180' : ''}`" />
            </button>
        </div>
        <div class="px-8 pb-4" v-if="isOpen">
            <p class="text-gray-200 text-xl font-light"><slot name="answer"></slot></p>
        </div>
    </div>
</template>

<style scoped></style>
