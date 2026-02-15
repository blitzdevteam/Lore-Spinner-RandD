<script setup lang="ts">
import { CreatorInterface } from '@/types';

defineProps<{
    creator: CreatorInterface
}>()
</script>

<template>
    {{creator}}
</template>

<style scoped>

</style>
