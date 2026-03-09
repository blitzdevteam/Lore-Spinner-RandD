<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { useTextToSpeech } from '@/composables/useTextToSpeech';
import { useTypewriter } from '@/composables/useTypewriter';
import { PromptInterface } from '@/types';
import { LucideCheck, LucideLoader, LucidePause, LucidePlay, LucideSparkles } from 'lucide-vue-next';
import { computed, onMounted, watch } from 'vue';

const CONTINUE_MARKER = '__continue__';

const props = defineProps<{
    prompt: PromptInterface;
    gameId: string;
    isLatest?: boolean;
    pendingChoice?: string;
    isSubmitting?: boolean;
    animate?: boolean;
}>();

const emit = defineEmits<{
    'choice-selected': [promptId: string, choice: string];
    continue: [];
}>();

const tts = useTextToSpeech();
const typewriter = useTypewriter();

const effectiveSelection = computed(() => {
    return props.prompt.prompt || props.pendingChoice || null;
});

const isContinued = computed(() => {
    return effectiveSelection.value === CONTINUE_MARKER;
});

const showQuotedAction = computed(() => {
    return effectiveSelection.value && !isContinued.value;
});

const canInteract = computed(() => {
    return props.isLatest && !effectiveSelection.value && !props.isSubmitting;
});

const showContinueButton = computed(() => {
    return props.isLatest && canInteract.value && props.prompt.response;
});

const renderedResponse = computed(() => {
    if (props.animate && props.isLatest) {
        return typewriter.displayedHtml.value;
    }
    return props.prompt.response;
});

const showChoicesAndActions = computed(() => {
    if (props.animate && typewriter.isTyping.value) {
        return false;
    }
    return true;
});

const getChoiceClass = (choice: string) => {
    const baseClass = 'p-3 rounded-lg border transition-all duration-300';

    if (!effectiveSelection.value) {
        return `${baseClass} border-gray-700 text-gray-100 cursor-pointer hover:bg-primary-400/10 hover:text-primary hover:border-primary-400`;
    }

    if (effectiveSelection.value === choice) {
        return `${baseClass} border-primary-400 bg-primary-400/10 text-primary pointer-events-none`;
    }

    return `${baseClass} border-gray-700/40 text-gray-400 opacity-50 pointer-events-none`;
};

const handleChoiceClick = (choice: string) => {
    if (!canInteract.value) return;
    emit('choice-selected', props.prompt.id, choice);
};

const handleContinue = () => {
    if (!canInteract.value) return;
    emit('continue');
};

const thisKey = computed(() => `${props.gameId}:${props.prompt.id}`);
const isThisPlaying = computed(() => tts.isPlaying.value && tts.activeKey.value === thisKey.value);
const isThisLoading = computed(() => tts.isLoading.value && tts.activeKey.value === thisKey.value);

const handlePlayToggle = () => {
    tts.toggle(props.gameId, props.prompt.id);
};

const handleNarrationClick = () => {
    if (typewriter.isTyping.value && props.prompt.response) {
        typewriter.skipToEnd(props.prompt.response);
    }
};

onMounted(() => {
    if (props.animate && props.isLatest && props.prompt.response) {
        typewriter.start(props.prompt.response);
    } else if (props.prompt.response) {
        typewriter.complete(props.prompt.response);
    }
});

watch(
    () => props.prompt.response,
    (newVal) => {
        if (!newVal) return;
        if (props.animate && props.isLatest) {
            typewriter.start(newVal);
        } else {
            typewriter.complete(newVal);
        }
    },
);
</script>

<template>
    <div class="flex flex-col gap-6 py-8">
        <!-- AI Response (narration) -->
        <div v-if="prompt.response" class="flex flex-col gap-2" @click="handleNarrationClick">
            <div class="font-light leading-relaxed" style="font-size: inherit" v-html="renderedResponse"></div>
            <span
                v-if="typewriter.isTyping.value"
                class="inline-block h-5 w-0.5 animate-pulse bg-primary-400 align-middle"
            />
        </div>

        <!-- Play / Continue buttons -->
        <div v-if="prompt.response && showChoicesAndActions" class="flex items-center gap-2">
            <BaseButton
                severity="primary-glass"
                :icon-only="true"
                class="size-12!"
                @click="handlePlayToggle"
            >
                <LucideLoader v-if="isThisLoading" class="size-5 animate-spin text-white" />
                <LucidePause v-else-if="isThisPlaying" fill="white" class="size-4 text-white" />
                <LucidePlay v-else fill="white" class="size-5 text-white" />
            </BaseButton>
            <BaseButton
                v-if="showContinueButton"
                severity="glass"
                class="rounded-full! py-1! ps-1.5! pe-3!"
                @click="handleContinue"
            >
                <div class="flex items-center gap-1.5">
                    <div class="relative grid size-9 place-items-center rounded-full">
                        <span
                            class="bg-primary-glass-effect absolute top-0 right-0 bottom-0 left-0 grid h-full w-full place-items-center rounded-full"
                        ></span>
                        <LucideSparkles fill="white" class="z-5 size-5 text-white" />
                    </div>
                    <span class="text-primary">Continue</span>
                </div>
            </BaseButton>
        </div>

        <!-- Choices (visible when they exist and animation is done) -->
        <div v-if="prompt.choices?.length && showChoicesAndActions" class="flex flex-col gap-2">
            <p v-if="canInteract" class="text-sm font-medium uppercase tracking-wider text-secondary-400">
                What do you do?
            </p>
            <div
                v-for="(choice, index) in prompt.choices"
                :key="choice"
                :class="getChoiceClass(choice)"
                @click="handleChoiceClick(choice)"
            >
                <div class="flex items-center gap-3">
                    <span
                        v-if="effectiveSelection === choice"
                        class="flex size-7 shrink-0 items-center justify-center rounded-full border border-primary bg-primary/20 text-primary"
                    >
                        <LucideCheck class="size-4" />
                    </span>
                    <span
                        v-else
                        class="flex size-7 shrink-0 items-center justify-center rounded-full border border-current text-sm"
                    >
                        {{ index + 1 }}
                    </span>
                    <p class="font-light">{{ choice }}</p>
                </div>
            </div>
        </div>

        <!-- Selected choice shown as styled quote (not for continue) -->
        <div v-if="showQuotedAction" class="border-l-2 border-primary pl-4 py-2">
            <p class="text-lg text-primary italic">"{{ effectiveSelection }}"</p>
        </div>
    </div>
</template>

<style scoped></style>
