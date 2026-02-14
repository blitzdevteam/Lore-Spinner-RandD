<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
}>()
</script>

<template>
    <div class="flex flex-col items-center gap-3 font-light">
        <h3 class="text-4xl font-semibold uppercase">{{title}}</h3>
        <p class="max-w-4xl text-center text-xl text-gray-200" v-if="$slots.description">
            <slot name="description" />
        </p>
    </div>
</template>

<style scoped>

</style>
