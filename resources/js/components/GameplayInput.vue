<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { useSpeechToText } from '@/composables/useSpeechToText';
import { LucideArrowUp, LucideLoader, LucideMic, LucideSquare } from 'lucide-vue-next';
import { ref } from 'vue';

const emit = defineEmits<{
    submit: [prompt: string];
}>();

const inputText = ref('');
const stt = useSpeechToText();

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

const handleMicToggle = async () => {
    if (stt.isRecording.value) {
        const text = await stt.stopRecording();
        if (text) {
            inputText.value = text;
        }
    } else {
        await stt.startRecording();
    }
};
</script>

<template>
    <div class="bg-glass-effect relative flex h-16 w-3xl items-center overflow-hidden rounded-full border border-gray-700 px-2">
        <div
            class="absolute top-0 right-0 bottom-0 left-0 h-full w-full bg-[linear-gradient(90deg,var(--color-primary-300)_0%,rgba(0,0,0,1)_25%)]"
        ></div>

        <!-- Mic button -->
        <BaseButton
            severity="primary-glass"
            :icon-only="true"
            class="relative z-10 ms-1 me-2 size-10! shrink-0"
            type="button"
            @click="handleMicToggle"
        >
            <LucideLoader v-if="stt.isTranscribing.value" class="size-5 animate-spin text-white" />
            <LucideSquare v-else-if="stt.isRecording.value" fill="white" class="size-3.5 text-white" />
            <LucideMic v-else class="size-5 text-white" />
        </BaseButton>

        <PrimeInputText
            v-model="inputText"
            class="relative w-full rounded-full! border-gray-700! bg-gray-800! p-2.5! shadow-none! outline-none! focus:border-primary-600!"
            :placeholder="stt.isRecording.value ? 'Listening...' : stt.isTranscribing.value ? 'Transcribing...' : 'What do you do?'"
            :disabled="stt.isRecording.value || stt.isTranscribing.value"
            @keydown="handleKeydown"
        />

        <!-- Recording pulse indicator -->
        <div
            v-if="stt.isRecording.value"
            class="absolute top-1/2 left-14 z-10 flex -translate-y-1/2 items-center gap-1.5"
        >
            <span class="inline-block size-2 animate-pulse rounded-full bg-red-500"></span>
        </div>

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
