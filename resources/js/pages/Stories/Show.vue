<script setup lang="ts">
import HomeLayout from '@/layouts/HomeLayout.vue';
import { StoryInterface } from '@/types';

defineProps<{
    story: StoryInterface
}>()
</script>

<template>
    <HomeLayout>
        {{story}}
    </HomeLayout>
</template>

<style scoped></style>
