<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { LucideArrowUp } from 'lucide-vue-next';
import { ref } from 'vue';

const emit = defineEmits<{
    submit: [prompt: string];
}>();

const inputText = ref('');

const handleSubmit = () => {
    const text = inputText.value.trim();
    if (!text) return;
    emit('submit', text);
    inputText.value = '';
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        handleSubmit();
    }
};
</script>

<template>
    <div class="bg-glass-effect relative flex h-16 w-3xl items-center overflow-hidden rounded-full border border-gray-700 px-2">
        <div
            class="absolute top-0 right-0 bottom-0 left-0 h-full w-full bg-[linear-gradient(90deg,var(--color-primary-300)_0%,rgba(0,0,0,1)_25%)]"
        ></div>
        <PrimeInputText
            v-model="inputText"
            class="relative w-full rounded-full! border-gray-700! bg-gray-800! p-2.5! shadow-none! outline-none! focus:border-primary-600!"
            placeholder="What do you do?"
            @keydown="handleKeydown"
        />
        <BaseButton
            severity="primary"
            :icon-only="true"
            class="absolute! end-3.75 size-8!"
            type="button"
            @click="handleSubmit"
        >
            <LucideArrowUp class="size-6 text-gray-900" />
        </BaseButton>
    </div>
</template>

<style scoped></style>
