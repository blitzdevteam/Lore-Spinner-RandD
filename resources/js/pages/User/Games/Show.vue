<script setup lang="ts">
import GameplayChatCard from '@/components/GameplayChatCard.vue';
import GameplaySidebarJournalEventCard from '@/components/GameplaySidebarJournalEventCard.vue';
import GameplayLayout from '@/layouts/GameplayLayout.vue';
import { GameInterface } from '@/types';
import { router } from '@inertiajs/vue3';
import { store as storePrompt } from '@/wayfinder/actions/App/Http/Controllers/User/Game/PromptController';

const props = defineProps<{
    game: GameInterface
}>()

const handleChoiceSelected = (id: number, choice: string) => {
    handleSubmit(choice)
}

const handleSubmit = (prompt: string) => {
    router.post(storePrompt(props.game.id), {
        prompt
    });
}
</script>

<template>
    <GameplayLayout>
        <template #game>
            <GameplayChatCard
                v-for="prompt in game.prompts"
                :prompt="prompt"
                @choice-selected="handleChoiceSelected"
            />
        </template>
        <template #journals>
            <GameplaySidebarJournalEventCard :is-passed="false" />
            <GameplaySidebarJournalEventCard :is-passed="true" />
            <GameplaySidebarJournalEventCard :is-passed="true" />
            <GameplaySidebarJournalEventCard :is-passed="true" />
        </template>
    </GameplayLayout>
</template>

<style scoped></style>
