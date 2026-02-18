<script setup lang="ts">
import GameplayLayout from '@/layouts/GameplayLayout.vue';
import GameplayChatCard from '@/components/GameplayChatCard.vue';
import GameplaySidebarJournalEventCard from '@/components/GameplaySidebarJournalEventCard.vue';

const data = [
    {
        prompt: 'You quietly push open the rusted gate of the abandoned lighthouse compound.',
        response: 'The hinges scream louder than thunder, echoing across the flooded courtyard, and somewhere deep inside the lighthouse a green lantern flickers once before vanishing, leaving the heavy silence to close around you like a warning that whatever waits inside has already heard your arrival.',
        choices: [
            'He looks back toward the road, wondering if he should call someone before losing signal.',
            'He ignores the pain and pushes forward, determined to finish before nightfall.',
            'He studies the crow it hasn’t moved and feels an irrational urge to throw a rock at it.'
        ],
        selectedChoice: 'He ignores the pain and pushes forward, determined to finish before nightfall.'
    },
    {
        prompt: 'You light the forbidden emerald lantern beneath your cloak.',
        response: 'The flame blooms into a cold jade glow that stains the fog and reveals symbols carved into the stone floor centuries ago, and as your shadow splits into three delayed reflections you realize the lantern is not illuminating the world around you, but slowly revealing a version of yourself you were never meant to meet.',
        choices: [
            'He looks back toward the road, wondering if he should call someone before losing signal.',
            'He ignores the pain and pushes forward, determined to finish before nightfall.',
            'He studies the crow it hasn’t moved and feels an irrational urge to throw a rock at it.'
        ]
    },
    {
        prompt: 'You follow the hooded stranger into the rain-soaked alleys of Vellmoor.',
        response: 'They glide through water that seems to avoid their boots, and when you turn the final corner they are gone, leaving only a spinning silver coin and a chalk eye on the wall that pulses faintly before fading, as if embarrassed to admit it was watching you the whole time.',
        choices: [
            'He looks back toward the road, wondering if he should call someone before losing signal.',
            'He ignores the pain and pushes forward, determined to finish before nightfall.',
            'He studies the crow it hasn’t moved and feels an irrational urge to throw a rock at it.'
        ]
    },
    {
        prompt: 'You confront the candle tax collector in front of the silent market crowd.',
        response: 'He listens without anger, dips his pen into ink darker than night, and calmly writes your name into his ledger while the crowd steps back in fear, because they know what you are only now beginning to understand—that debts in Vellmoor are never forgiven, only reassigned.',
        choices: [
            'He looks back toward the road, wondering if he should call someone before losing signal.',
            'He ignores the pain and pushes forward, determined to finish before nightfall.',
            'He studies the crow it hasn’t moved and feels an irrational urge to throw a rock at it.'
        ],
    }
];
</script>

<template>
    <GameplayLayout>
        <template #game>
            <GameplayChatCard
                v-for="a in data"
                :prompt="a.prompt"
                :response="a.response"
                :choices="a.choices"
                :selected-choice="a.selectedChoice"
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

<style scoped>

</style>
