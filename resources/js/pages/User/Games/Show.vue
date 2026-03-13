<script setup lang="ts">
import GameOpeningNarration from '@/components/GameOpeningNarration.vue';
import GameplayChatCard from '@/components/GameplayChatCard.vue';
import GameplaySidebarJournalEventCard from '@/components/GameplaySidebarJournalEventCard.vue';
import GameplayLayout from '@/layouts/GameplayLayout.vue';
import { EventInterface, GameInterface } from '@/types';
import { router } from '@inertiajs/vue3';
import { store as storePrompt } from '@/wayfinder/actions/App/Http/Controllers/User/Game/PromptController';
import { LucideUser } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref } from 'vue';

const CONTINUE_MARKER = '__continue__';

const props = defineProps<{
    game: GameInterface;
}>();

const journalEvents = computed(() => {
    const seen = new Set<number>();
    const events: (EventInterface & { isCurrent: boolean })[] = [];

    for (const prompt of prompts.value) {
        if (prompt.event && !seen.has(prompt.event.id)) {
            seen.add(prompt.event.id);
            events.push({
                ...prompt.event,
                isCurrent: prompt.event.id === props.game.current_event_id,
            });
        }
    }

    return events;
});

interface CharacterSheet {
    name: string;
    firstEventTitle: string;
    appearances: string[];
    lastLocation: string | null;
    conditions: string[];
}

const characters = computed(() => {
    const charMap = new Map<string, CharacterSheet>();

    for (const prompt of prompts.value) {
        const attrs = prompt.event?.attributes;
        if (!attrs) continue;

        const eventTitle = prompt.event?.title ?? 'Unknown';
        let eventLocation: string | null = null;
        let eventConditions: string[] = [];
        let eventCharNames: string[] = [];

        for (const attr of attrs) {
            if (attr.startsWith('Characters physically present:')) {
                eventCharNames = attr
                    .replace('Characters physically present:', '')
                    .split('|')
                    .map((n) => n.trim())
                    .filter(Boolean);
            } else if (attr.startsWith('Location:')) {
                eventLocation = attr.replace('Location:', '').trim() || null;
            } else if (attr.startsWith('Persistent physical conditions:')) {
                eventConditions = attr
                    .replace('Persistent physical conditions:', '')
                    .split('|')
                    .map((c) => c.trim())
                    .filter(Boolean);
            }
        }

        for (const name of eventCharNames) {
            const existing = charMap.get(name);
            if (existing) {
                if (!existing.appearances.includes(eventTitle)) {
                    existing.appearances.push(eventTitle);
                }
                if (eventLocation) existing.lastLocation = eventLocation;
                const relevantConditions = eventConditions.filter(
                    (c) => c.toLowerCase().includes(name.toLowerCase()) || eventCharNames.length === 1,
                );
                for (const cond of relevantConditions) {
                    if (!existing.conditions.includes(cond)) {
                        existing.conditions.push(cond);
                    }
                }
            } else {
                const relevantConditions = eventConditions.filter(
                    (c) => c.toLowerCase().includes(name.toLowerCase()) || eventCharNames.length === 1,
                );
                charMap.set(name, {
                    name,
                    firstEventTitle: eventTitle,
                    appearances: [eventTitle],
                    lastLocation: eventLocation,
                    conditions: [...relevantConditions],
                });
            }
        }
    }

    return Array.from(charMap.values());
});

const handleBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.visit('/');
    }
};

const isSubmitting = ref(false);
const isAutoBeginning = ref(false);
const pendingSelection = ref<Record<string, string>>({});
const shouldAnimate = ref(false);

const prompts = computed(() => props.game.prompts ?? []);
const hasPrompts = computed(() => prompts.value.length > 0);
const storyOpening = computed(() => props.game.story?.opening ?? null);
const showOpening = computed(() => !hasPrompts.value && !!storyOpening.value);

const canSubmitInput = computed(() => {
    if (isSubmitting.value) return false;
    const latest = prompts.value[prompts.value.length - 1];
    return latest && !latest.prompt;
});

const handleBegin = () => {
    router.post(
        `/user/games/${props.game.id}/begin`,
        {},
        {
            preserveScroll: false,
            onSuccess: () => {
                shouldAnimate.value = true;
            },
            onFinish: () => {
                nextTick(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            },
        },
    );
};

const handleChoiceSelected = (promptId: string, choice: string) => {
    if (isSubmitting.value) return;
    pendingSelection.value[promptId] = choice;
    submitPrompt(choice);
};

const handleContinue = () => {
    if (isSubmitting.value) return;
    const latestPrompt = prompts.value[prompts.value.length - 1];
    if (latestPrompt) {
        pendingSelection.value[latestPrompt.id] = CONTINUE_MARKER;
    }
    submitPrompt(CONTINUE_MARKER);
};

const handleSubmit = (prompt: string) => {
    if (isSubmitting.value) return;
    submitPrompt(prompt);
};

const submitPrompt = (prompt: string) => {
    isSubmitting.value = true;

    router.post(
        storePrompt(props.game.id),
        { prompt },
        {
            preserveScroll: true,
            onSuccess: () => {
                shouldAnimate.value = true;
            },
            onFinish: () => {
                isSubmitting.value = false;
                pendingSelection.value = {};

                nextTick(() => {
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                });
            },
        },
    );
};

onMounted(() => {
    if (hasPrompts.value) {
        nextTick(() => {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        });
    } else if (!storyOpening.value) {
        isAutoBeginning.value = true;
        handleBegin();
    }
});
</script>

<template>
    <!-- Opening narration phase -->
    <GameOpeningNarration
        v-if="showOpening"
        :opening="storyOpening!"
        :story-title="game.story?.title ?? 'the story'"
        @begin="handleBegin"
    />

    <!-- Loading state while auto-beginning (no opening available) -->
    <div v-else-if="isAutoBeginning" class="grid h-svh place-items-center bg-gray-950">
        <div class="flex flex-col items-center gap-4">
            <div class="size-8 animate-spin rounded-full border-2 border-primary-400 border-t-transparent" />
            <p class="text-sm text-gray-400">Preparing your adventure...</p>
        </div>
    </div>

    <!-- Gameplay phase -->
    <GameplayLayout v-else :input-disabled="!canSubmitInput" @submit="handleSubmit" @back="handleBack">
        <template #header>
            <div class="hidden flex-col gap-1.5 md:flex">
                <h1 class="text-xl uppercase md:text-3xl">{{ (game as any).currentEvent?.title ?? 'Adventure' }}</h1>
                <div v-if="(game as any).currentEvent?.chapter?.position">
                    <span class="rounded-full bg-gray-800 px-2 py-1 text-sm">
                        Chapter {{ (game as any).currentEvent.chapter.position }}
                    </span>
                </div>
            </div>
        </template>

        <template #game>
            <GameplayChatCard
                v-for="prompt in prompts"
                :key="prompt.id"
                :prompt="prompt"
                :game-id="game.id"
                :is-latest="prompt.id === prompts[prompts.length - 1]?.id"
                :pending-choice="pendingSelection[prompt.id]"
                :is-submitting="isSubmitting"
                :animate="shouldAnimate && prompt.id === prompts[prompts.length - 1]?.id"
                @choice-selected="handleChoiceSelected"
                @continue="handleContinue"
            />

            <!-- Loading skeleton while AI generates the next response -->
            <div v-if="isSubmitting" class="py-8">
                <div class="flex flex-col gap-4 animate-pulse">
                    <div class="h-4 w-full rounded bg-gray-700/50"></div>
                    <div class="h-4 w-5/6 rounded bg-gray-700/50"></div>
                    <div class="h-4 w-3/4 rounded bg-gray-700/50"></div>
                    <div class="h-4 w-4/6 rounded bg-gray-700/40"></div>
                </div>
            </div>
        </template>

        <template #journals>
            <p v-if="!journalEvents.length" class="text-sm text-gray-500">No events yet.</p>
            <GameplaySidebarJournalEventCard
                v-for="event in journalEvents"
                :key="event.id"
                :title="event.title"
                :objective="event.objectives"
                :is-current="event.isCurrent"
            />
        </template>

        <template #characters>
            <p v-if="!characters.length" class="text-sm text-gray-500">No characters introduced yet.</p>
            <div v-for="char in characters" :key="char.name" class="rounded-xl border border-gray-700/50 bg-gray-800/40 p-4 transition-all hover:border-gray-600">
                <div class="flex items-center gap-3">
                    <div class="grid size-10 shrink-0 place-items-center rounded-full bg-secondary-400/10 text-secondary-400">
                        <LucideUser class="size-5" :stroke-width="1.5" />
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <h6 class="text-base text-gray-100">{{ char.name }}</h6>
                        <p class="text-xs text-gray-500">First appeared in: {{ char.firstEventTitle }}</p>
                    </div>
                </div>
                <div v-if="char.lastLocation || char.conditions.length || char.appearances.length > 1" class="mt-3 flex flex-col gap-1.5 border-t border-gray-700/40 pt-3">
                    <div v-if="char.lastLocation" class="flex items-baseline gap-2 text-xs">
                        <span class="shrink-0 font-semibold uppercase text-gray-500">Last seen</span>
                        <span class="text-gray-300">{{ char.lastLocation }}</span>
                    </div>
                    <div v-if="char.conditions.length" class="flex items-baseline gap-2 text-xs">
                        <span class="shrink-0 font-semibold uppercase text-gray-500">Status</span>
                        <span class="text-gray-300">{{ char.conditions.join(' · ') }}</span>
                    </div>
                    <div v-if="char.appearances.length > 1" class="flex items-baseline gap-2 text-xs">
                        <span class="shrink-0 font-semibold uppercase text-gray-500">Seen in</span>
                        <span class="text-gray-400">{{ char.appearances.length }} events</span>
                    </div>
                </div>
            </div>
        </template>
    </GameplayLayout>
</template>

<style scoped></style>
