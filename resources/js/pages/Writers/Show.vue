<script setup lang="ts">
import { WriterInterface } from '@/types';

defineProps<{
    writer: WriterInterface
}>()
</script>

<template>
    {{writer}}
</template>

<style scoped>

</style>
