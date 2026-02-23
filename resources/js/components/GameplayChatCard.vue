<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { LucidePlay, LucideSparkles } from 'lucide-vue-next';
import { PromptInterface } from '@/types';

const props = defineProps<{
    prompt: PromptInterface;
    previousPrompt?: PromptInterface;
}>();

const emit = defineEmits<{
    'choice-selected': (id: number, choice: string) => void;
}>();

const getChoiceClass = (choice: string) => {
    const baseClass = 'p-3 rounded-lg border cursor-pointer transition-colors';

    if (props.previousPrompt?.prompt === choice) {
        return `${baseClass} border-primary-400 bg-primary-400/10 text-primary pointer-events-none`;
    }

    if (props.previousPrompt?.prompt) {
        return `${baseClass} border-gray-700 text-gray-100 opacity-50 pointer-events-none`;
    }

    return `${baseClass} border-gray-700 text-gray-100 hover:bg-primary-400/10 hover:text-primary hover:border-primary-400`;
};

const handleChoiceSelected = (choice: string) => {
    if (props.previousPrompt?.prompt) {
        return;
    }

    emit('choice-selected', props.prompt.id, choice);
};
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <template v-if="previousPrompt">
                <p class="py-2 text-lg text-secondary-300">{{ previousPrompt }}</p>
            </template>
            <template v-if="prompt.response">
                <div class="text-lg font-light" v-html="prompt.response"></div>
            </template>
        </div>
        <template v-if="prompt.response">
            <div
                v-if="prompt.choices.length"
                class="flex flex-col gap-2"
            >
                <div
                    v-for="choice in prompt.choices"
                    :key="choice"
                    :class="getChoiceClass(choice)"
                    @click="handleChoiceSelected(choice)"
                >
                    <p class="font-light">{{ choice }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <BaseButton severity="primary-glass" :icon-only="true" class="size-12!">
                    <LucidePlay fill="white" class="size-5 text-white" />
                </BaseButton>
                <BaseButton severity="glass" class="rounded-full! py-1! ps-1.5! pe-3!">
                    <div class="flex items-center gap-1.5">
                        <div class="relative grid size-9 place-items-center rounded-full">
                            <span
                                class="bg-primary-glass-effect absolute top-0 right-0 bottom-0 left-0 grid h-full w-full place-items-center rounded-full"></span>
                            <LucideSparkles fill="white" class="z-5 size-5 text-white" />
                        </div>
                        <span class="text-primary">Continue</span>
                    </div>
                </BaseButton>
            </div>
        </template>
    </div>
</template>

<style scoped></style>
